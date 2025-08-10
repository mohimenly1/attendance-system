from flask import Flask, request, jsonify
from dotenv import load_dotenv
import os
from face_encoder import encode_faces # Import our function
import pickle
import face_recognition
import numpy as np
import io
from PIL import Image

# Load environment variables from .env file
load_dotenv()

app = Flask(__name__)

# --- Add this new endpoint ---
@app.route('/recognize-face', methods=['POST'])
def handle_recognize_face():
    # Check if the encoding file exists
    if not os.path.exists("encodings.pickle"):
        return jsonify({"error": "Encoding data not found. Please run the encoding process first."}), 500

    # Load the known faces and embeddings
    with open("encodings.pickle", "rb") as f:
        data = pickle.load(f)

    # Check if a file was sent
    if 'image' not in request.files:
        return jsonify({"error": "No image file provided."}), 400

    file = request.files['image']

    # Read the image file
    image_stream = io.BytesIO(file.read())
    image = Image.open(image_stream)

    # Convert image to RGB (needed for face_recognition)
    rgb_image = np.array(image.convert('RGB'))

    # Find all faces and their encodings in the current frame
    face_locations = face_recognition.face_locations(rgb_image)
    face_encodings = face_recognition.face_encodings(rgb_image, face_locations)

    recognized_ids = []
    # Loop over each face found in the frame
    for encoding in face_encodings:
        # See if the face is a match for the known face(s)
        matches = face_recognition.compare_faces(data["encodings"], encoding)

        # Use the known face with the smallest distance to the new face
        face_distances = face_recognition.face_distance(data["encodings"], encoding)
        best_match_index = np.argmin(face_distances)

        if matches[best_match_index]:
            student_id = data["ids"][best_match_index]
            recognized_ids.append(student_id)

    if recognized_ids:
        # Return the first recognized ID for simplicity
        return jsonify({"student_id": recognized_ids[0]}), 200
    else:
        return jsonify({"message": "No known student recognized."}), 404

@app.route('/encode-faces', methods=['POST'])
def handle_encode_faces():
    # Get data from the request sent by Laravel
    data = request.get_json()

    if not data or 'students' not in data:
        return jsonify({"error": "Invalid data provided. 'students' key is missing."}), 400

    students_data = data['students']

    # Get the path to Laravel's storage from .env file
    # Example: LARAVEL_STORAGE_PATH=/path/to/your/laravel_project/storage/app/public
    laravel_storage_path = os.getenv("LARAVEL_STORAGE_PATH")

    if not laravel_storage_path:
         return jsonify({"error": "LARAVEL_STORAGE_PATH is not set in the .env file."}), 500

    # Call our encoding function
    success = encode_faces(students_data, laravel_storage_path)

    if success:
        return jsonify({"message": "Faces encoded successfully."}), 200
    else:
        return jsonify({"error": "Failed to encode any faces."}), 500

if __name__ == '__main__':
    # Run the Flask app
    app.run(debug=True, port=5000)