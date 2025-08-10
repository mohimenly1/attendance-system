import face_recognition
import pickle
import os

def encode_faces(students_data, base_path):
    """
    Encodes faces from student photos and saves them to a file.

    :param students_data: A dictionary like {'student_id': [path1, path2], ...}
    :param base_path: The base path of the Laravel project's storage.
    :return: True if successful, False otherwise.
    """
    known_encodings = []
    known_ids = []

    print("Starting face encoding process...")

    for student_id, photo_paths in students_data.items():
        for photo_path in photo_paths:
            # Construct the full path to the image
            full_image_path = os.path.join(base_path, photo_path)

            if not os.path.exists(full_image_path):
                print(f"Warning: Image not found at {full_image_path}")
                continue

            try:
                # Load the image and find face encodings
                image = face_recognition.load_image_file(full_image_path)
                # We assume one face per photo for simplicity
                encodings = face_recognition.face_encodings(image)

                if encodings:
                    encoding = encodings[0]
                    known_encodings.append(encoding)
                    known_ids.append(student_id)
                    print(f"Successfully encoded photo for student ID: {student_id}")
                else:
                    print(f"Warning: No face found in {full_image_path} for student ID: {student_id}")

            except Exception as e:
                print(f"Error processing image {full_image_path}: {e}")
                continue

    if not known_encodings:
        print("No faces were encoded. Aborting.")
        return False

    # Save the encodings and IDs to a file
    encoding_data = {"encodings": known_encodings, "ids": known_ids}

    with open("encodings.pickle", "wb") as f:
        pickle.dump(encoding_data, f)

    print("Face encoding process finished successfully. `encodings.pickle` file created.")
    return True