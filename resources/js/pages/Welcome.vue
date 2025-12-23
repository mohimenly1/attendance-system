<template>
  <!-- الغلاف العام للصفحة، محدد اتجاه الكتابة من اليمين لليسار ويمد طول الصفحة كامل -->
  <div dir="rtl" class="min-h-screen">

    <!-- ================= Navbar | شريط التنقل العلوي ================= -->
    <header class="header nav-polished">
      <nav class="nav-wrap">

        <!-- Logo | الشعار واسم النظام -->
        <a href="#home" class="brand">
          <!-- أيقونة تدل على نظام حضور -->
          <i class="fas fa-user-check"></i>
          <!-- اسم النظام -->
          <span class="logo">رؤية المرقب</span>
        </a>

        <!-- Links | روابط التنقل بين أقسام الصفحة -->
        <div class="nav-links">
          <a href="#home" class="is-active">الرئيسية</a> <!-- يرجع لبداية الصفحة -->
          <a href="#about">حول النظام</a> <!-- تعريف بالنظام -->
          <a href="#features">مميزات النظام</a> <!-- مميزات -->
          <a href="#team">الفريق</a> <!-- فريق العمل -->
          <a href="#contact">تواصل معنا</a> <!-- نموذج التواصل -->
        </div>

        <!-- Actions | أزرار التحكم (الوضع + دخول + تسجيل) -->
        <div class="nav-actions">
          <!-- زر تبديل الوضع (فاتح / داكن) -->
          <button class="theme-btn" @click="toggleTheme" title="تبديل الوضع">
            <!-- الأيقونة تتغير حسب حالة الوضع -->
            <i :class="isLight ? 'far fa-moon' : 'far fa-sun'"></i>
            <span>{{ isLight ? 'Light' : 'Dark' }}</span>
          </button>

          <!-- روابط الدخول والتسجيل (بدون vue-router) -->
          <!-- لو تستعمل vue-router يتبدلوا بـ router-link -->
          <a href="/login" class="auth-link"> دخول</a>
          <a href="/register" class="auth-cta">تسجيل</a>
        </div>
      </nav>

      <!-- عنصر حركي للزخرفة -->
      <span class="animate" style="--i:1"></span>
    </header>

    <!-- ================= Home | الواجهة الرئيسية ================= -->
    <section id="home" class="home hero-blue" style="height:100vh;">
      <div class="home-content">

        <!-- عنوان متحرك -->
        <div class="text-animate">
          <h3>رؤية المرقب </h3>
        </div>

        <!-- فراغ بصري فقط للتنسيق -->
        <pre>


</pre>

        <!-- العنوان الرئيسي للنظام -->
        <h1><span>نموذج تطبيقي لأتمتة عملية تسجيل الحضور الطلابي </span></h1>

        <!-- وصف مختصر لفكرة النظام -->
        <p>
          هذا النظام هو نموذج تجريبي لتطبيق تقنية التعرّف على الوجه في تسجيل حضور الطلاب في جامعة المرقب. يركّز المشروع على استعراض إمكانيات التعرّف الآلي على الوجوه، وتقديم واجهة مبسّطة لإدارة الجداول والتقارير الأكاديمية بشكل فعّال، ويقدّم نظرة عملية على كيفية استخدام هذه التقنية لتحسين التجربة التعليمية.
        </p>

        <!-- زر ينقل لقسم "حول النظام" -->
        <div class="btn-box">
          <!-- أصناف shimmer و pulse للحركة -->
          <a href="#about" class="btn shimmer pulse">تعرف على النظام</a>
        </div>
      </div>

      <!-- صورة الجامعة أو الشعار (تظهر على اليسار) -->
      <div class="home-imgHover">
        <img class="spin-move" src="/images/logo-mergeb.png" alt="جامعة المرقب" />
      </div>

      <!-- عناصر حركية -->
      <span class="animate" style="--i:2"></span>
      <span class="animate home-img" style="--i:3"></span>
    </section>

    <!-- ================= About | حول النظام ================= -->
    <section id="about" class="about">
      <h2 class="heading">حول <span>النظام</span></h2>

      <!-- شبكة الكروت التعريفية -->
      <div class="about-grid">
        <div class="about-card">
          <h3>أتمتة عملية تسجيل الحضور</h3>
          <p>يعتمد النظام على بصمة الوجه لتسجيل الحضور تلقائيًا دون تلامس،
    مع تأكيد فوري وتحديث لحظي في لوحة التحكم لتقليل الجهد الإداري.</p>
        </div>

        <div class="about-card">
          <h3>تكامل مع الجداول</h3>
          <p>
    مزامنة مبسّطة  تلقائية مع المواد والقاعات والدكاترة؛ تُنشأ جلسات الحضور تلقائيًا
    وفق جدول المادة وتُحدّث عند أي تغيير.</p>
        </div>

        <div class="about-card">
          <h3>صلاحيات وأمان متكامل</h3>
          <p>
    يوفّر النظام صلاحيات مخصّصة لكل دور (مدير، معلم، طالب) مع تشفير للبيانات الحساسة،
    ونظام تحقق بديل يرسل رمزًا عبر البريد الإلكتروني عند فشل التعرّف على الوجه لضمان دقة الحضور.</p>
        </div>

        <div class="about-card">
          <h3>تقارير فورية</h3>
          <p>
    يوفّر النظام لوحة متابعة لحظية تعرض حالة الحضور والغياب مباشرة،
    مع إمكانية تصدير التقارير بصيغ مختلفة للاستخدام الأكاديمي والإداري.</p>
        </div>
      </div>

      <!-- حركة عند النزول -->
      <span class="animate scroll" style="--i:1"></span>
    </section>

    <!-- ================= Showcase | استعراض واجهات النظام ================= -->
    <section id="showcase" class="showcase">
      <h2 class="heading">واجهات <span>النظام</span></h2>

      <!-- Tabs | أزرار التبديل بين الواجهات -->
      <div class="sc-tabs" role="tablist" aria-label="واجهات النظام">
        <button :class="['sc-tab', currentShot==='admin' && 'active']" @click="setShot('admin')" role="tab">لوحة المشرف</button>
        <button :class="['sc-tab', currentShot==='teacher' && 'active']" @click="setShot('teacher')" role="tab">واجهة المعلم</button>
        <button :class="['sc-tab', currentShot==='student' && 'active']" @click="setShot('student')" role="tab">واجهة الطالب</button>
      </div>

      <!-- عرض الواجهة داخل جهاز ثلاثي الأبعاد -->
      <div class="sc-stage" ref="stageRef">
        <div class="device" ref="deviceRef">
          <!-- صورة الواجهة الحالية -->
          <img class="screen" :src="shots[currentShot].img" :alt="shots[currentShot].title" />
          <!-- شارات توضيحية -->
          <div class="badge b1">Realtime</div>
          <div class="badge b2">Reports</div>
          <div class="badge b3">Secure</div>
          <span class="shine"></span>
        </div>
      </div>

      <!-- وصف الواجهة المختارة -->
      <p class="sc-caption">{{ shots[currentShot].desc }}</p>

      <!-- صور مصغرة للتبديل بين الواجهات -->
      <div class="sc-thumbs">
        <button v-for="(s, key) in shots" :key="key" class="thumb" :class="{active: currentShot===key}" @click="setShot(key)">
          <img :src="s.thumb" :alt="s.title" />
        </button>
      </div>
    </section>

    <!-- ================= Features | مميزات النظام ================= -->
    <section id="features" class="features clean">
      <h2 class="heading">ممي<span>زات</span> النظام</h2>

      <div class="feat-grid">
        <article class="feat">
          <i class="fas fa-bolt feat-ico" aria-hidden="true"></i>
          <div>
            <h4>تتبّع فوري</h4>
            <p>تسجيل الحضور لحظيًا مع تزامن آني مع الخادم.</p>
          </div>
        </article>

        <article class="feat">
          <i class="fas fa-bell feat-ico" aria-hidden="true"></i>
          <div>
            <h4>تنبيهات ذكية</h4>
            <p>إشعارات لاندارات الغياب المتكرر وإرسال بريد بديل عند فشل التعرّف.</p>
          </div>
        </article>

        <article class="feat">
          <i class="fas fa-filter feat-ico" aria-hidden="true"></i>
          <div>
            <h4>بحث وترشيح</h4>
            <p>فلترة سريعة للتقارير حسب المادة، التاريخ، والحالة.</p>
          </div>
        </article>

        <article class="feat">
          <i class="fas fa-camera feat-ico" aria-hidden="true"></i>
          <div>
            <h4>تكامل الكاميرا</h4>
            <p>التقاط مباشر من المتصفح وإرسال الإطار تلقائيًا للمطابقة.</p>
          </div>
        </article>

        <article class="feat">
          <i class="fas fa-shield-alt feat-ico" aria-hidden="true"></i>
          <div>
            <h4>أمان وصلاحيات</h4>
            <p>أدوار (مدير/معلم/طالب) وتشفير للبيانات الحساسة.</p>
          </div>
        </article>

        <article class="feat">
          <i class="fas fa-file-export feat-ico" aria-hidden="true"></i>
          <div>
            <h4>تقارير وتصدير</h4>
            <p>تقارير لحظية مع تصدير CSV/PDF للاستخدام الأكاديمي.</p>
          </div>
        </article>
      </div>
    </section>

    <!-- ================= Team | فريق العمل ================= -->
    <section id="team" class="team-sec">
      <h2 class="heading">الف<span>ريق</span></h2>

      <div class="team-row">
        <!-- تكرار عناصر الفريق من المصفوفة -->
        <div v-for="m in team" :key="m.name" class="team-box">
          <div class="about-img small">
            <img :src="m.photo" :alt="m.name" />
            <span class="circle-spin"></span>
          </div>
          <h3 class="team-name">{{ m.name }}</h3>
          <p class="team-role">{{ m.role }}</p>
          <p class="team-bio">{{ m.bio }}</p>
        </div>
      </div>

      <span class="animate scroll" style="--i:3"></span>
    </section>

    <!-- ================= Contact | التواصل ================= -->
    <section id="contact" class="contact">
      <h2 class="heading">تواصل <span>معنا</span></h2>

      <!-- نموذج إرسال رسالة -->
      <form id="contactForm" @submit.prevent="onSubmit">
        <div class="input-box">
          <div class="input-field">
            <input id="name" type="text" required placeholder="الاسم" />
            <span class="focus"></span>
          </div>
          <div class="input-field">
            <input id="number" type="number" required placeholder="الهاتف" />
            <span class="focus"></span>
          </div>
        </div>

        <div class="input-box">
          <div class="input-field">
            <input id="email" type="email" required placeholder="البريد الإلكتروني" />
            <span class="focus"></span>
          </div>
          <div class="input-field">
            <input id="subject" type="text" required placeholder="الموضوع" />
            <span class="focus"></span>
          </div>
        </div>

        <div class="textarea-field">
          <textarea id="message" rows="6" required placeholder="الرسالة"></textarea>
          <span class="focus"></span>
        </div>

        <div class="btn-box btns">
          <!-- نفس تأثير الأزرار السابقة -->
          <button type="submit" class="btn shimmer pulse">إرسال</button>
        </div>

        <p id="Verification"></p>
      </form>

      <span class="animate scroll" style="--i:4"></span>
    </section>

    <!-- ================= Footer | التذييل ================= -->
    <footer class="footer">
      <div class="footer-text">
        <p>© {{ new Date().getFullYear() }} FaceAttendance — جامعة المرقب.</p>
      </div>
      <div class="footer-iconTop">
        <!-- زر الرجوع لأعلى الصفحة -->
        <a href="#home" aria-label="أعلى"><i class="fas fa-arrow-up"></i></a>
      </div>
      <span class="animate scroll" style="--i:5"></span>
    </footer>
  </div>
</template>


<script setup>
/* (لم أغير أي شيء هنا) */
import { ref, onMounted, onBeforeUnmount } from 'vue'

/** Theme (Light/Dark) */
const isLight = ref(false)
const applyTheme = (light) => {
  isLight.value = light
  const root = document.documentElement
  if (light) {
    root.classList.add('light-mode')
    localStorage.setItem('theme', 'light')
  } else {
    root.classList.remove('light-mode')
    localStorage.setItem('theme', 'dark')
  }
}
const toggleTheme = () => applyTheme(!isLight.value)

/** Team */
const team = [
  { name: 'آلاء خيري أبورقيقة',  role: 'مهندسة برمجيات ومطورة ويب',  photo: '/images/women.png', bio: '' },
  { name: 'إشراق بشير عليوان',  role: 'مهندسة برمجيات',   photo: '/images/women.png', bio: '' },
  { name: 'د.حمزة شخطور',role: 'المشرف',photo: '/images/men.png', bio: '' },
]

/** Showcase */
const currentShot = ref('admin')
const shots = {
  admin: { title: 'لوحة المشرف', img: '/images/adminDashpord.png', thumb: '/images/adminDashpord.png', desc: 'إدارة الجداول، الشعب، المستخدمين، والصلاحيات مع تقارير فورية.' },
  teacher: { title: 'واجهة المعلم', img: '/images/shots/teacher.png', thumb: '/images/shots/teacher.png', desc: 'بدء جلسة الحضور بالكاميرا، متابعة الطلاب، وتنزيل تقارير المادة.' },
  student: { title: 'واجهة الطالب', img: '/images/shots/student.png', thumb: '/images/shots/student.png', desc: 'تأكيد الحضور ببصمة الوجه ومشاهدة السجل والتنبيهات.' }
}
const setShot = (key) => { currentShot.value = key }

const stageRef = ref(null)
const deviceRef = ref(null)

let raf = null
const onPointerMove = (e) => {
  const card = deviceRef.value
  if (!card) return
  const r = card.getBoundingClientRect()
  const x = (e.clientX - r.left) / r.width
  const y = (e.clientY - r.top) / r.height
  const rx = (0.5 - y) * 10
  const ry = (x - 0.5) * 12
  const deg = Math.atan2(e.clientY - (r.top + r.height/2), e.clientX - (r.left + r.width/2)) * 180 / Math.PI
  cancelAnimationFrame(raf)
  raf = requestAnimationFrame(() => {
    card.style.setProperty('--rx', `${rx}deg`)
    card.style.setProperty('--ry', `${ry}deg`)
    card.style.setProperty('--shine', `${deg}deg`)
  })
}
const onPointerLeave = () => {
  const card = deviceRef.value
  if (!card) return
  card.style.setProperty('--rx', `0deg`)
  card.style.setProperty('--ry', `0deg`)
}

/** Scroll spy + sticky + footer animate + reveal */
const handleScroll = () => {
  const sections = document.querySelectorAll('section')
  const navLinks = document.querySelectorAll('.nav-links a')
  const top = window.scrollY

  sections.forEach((sec) => {
    const offset = (sec.offsetTop || 0) - 100
    const height = sec.offsetHeight || 0
    const id = sec.getAttribute('id')

    if (top >= offset && top < offset + height) {
      navLinks.forEach(a => a.classList.remove('is-active'))
      const active = document.querySelector(`.nav-links a[href*="${id}"]`)
      active?.classList.add('is-active')
      sec.classList.add('show-animate')
    } else {
      sec.classList.remove('show-animate')
    }
  })

  const header = document.querySelector('.header')
  header?.classList.toggle('sticky', window.scrollY > 100)

  const footer = document.querySelector('footer')
  if (footer) {
    const atBottom = window.innerHeight + window.scrollY >= document.scrollingElement.scrollHeight
    footer.classList.toggle('show-animate', atBottom)
  }
}

/** Contact form validation helpers */
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

/** onSubmit: defined to avoid runtime error when template uses @submit.prevent.
 *  يقوم بالتحقق من الحقول ويعرض رسالة قصيرة في عنصر #Verification
 */
const onSubmit = (e) => {
  // e.preventDefault(); // not needed because @submit.prevent used in template
  let ok = true
  const name = document.getElementById('name')
  const number = document.getElementById('number')
  const email = document.getElementById('email')
  const subject = document.getElementById('subject')
  const message = document.getElementById('message')
  const out = document.getElementById('Verification')

  if (!name?.value.trim()) ok = false
  if (!number?.value.trim()) ok = false
  if (!subject?.value.trim()) ok = false
  if (!email?.value.trim() || !isValidEmail(email.value.trim())) ok = false
  if (!message?.value.trim()) ok = false

  if (out) {
    if (ok) {
      out.textContent = 'Form submitted successfully!'
      // هنا يمكنك إرسال الطلب فعلياً عبر fetch إذا أردت
      e.target.reset?.()
    } else {
      out.textContent = 'Form submitted not successfully! تحقق من الحقول'
      // لا تمسح القيم لتسمح للمستخدم بتصحيحها:
      // form.reset() // تم تعطيله عمداً
    }
  }
}

/** wireContactForm: يُترك كمكمل (لمن يريد استخدام الربط عبر DOM بدل onSubmit) */
const wireContactForm = () => {
  const form = document.getElementById('contactForm')
  if (!form) return
  // تم الاحتفاظ بالوظيفة لكن لم نعد نربط submit هنا لأنها معرفة في onSubmit
}

onMounted(() => {
  const saved = localStorage.getItem('theme') || 'dark'
  applyTheme(saved === 'light')

  document.documentElement.style.scrollBehavior = 'smooth'

  const header = document.querySelector('.header')
  const stickyCheck = () => header?.classList.toggle('sticky', window.scrollY > 10)
  stickyCheck()
  window.addEventListener('scroll', stickyCheck, { passive: true })

  const sections = document.querySelectorAll('section')
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (e.isIntersecting) {
        e.target.classList.add('show-animate')
        if (e.target.id === 'about') {
          const cards = e.target.querySelectorAll('.about-card')
          cards.forEach((card, idx) => setTimeout(() => card.classList.add('in-view'), idx * 100))
        }
        io.unobserve(e.target)
      }
    })
  }, { threshold: 0.15 })
  sections.forEach((s) => io.observe(s))

  const links = Array.from(document.querySelectorAll('.nav-links a'))
  const byHash = Object.fromEntries(links.map(a => [a.getAttribute('href'), a]))
  const setActive = (hash) => {
    links.forEach(a => a.classList.remove('is-active'))
    const key = hash && hash !== '#' ? hash : '#home'
    byHash[key]?.classList.add('is-active')
  }
  setActive(location.hash || '#home')
  links.forEach(a => a.addEventListener('click', () => setActive(a.getAttribute('href'))))
  window.addEventListener('hashchange', () => setActive(location.hash))

  const stage = stageRef.value
  stage?.addEventListener('pointermove', onPointerMove)
  stage?.addEventListener('pointerleave', onPointerLeave)

  window.addEventListener('scroll', handleScroll, { passive: true })
  handleScroll()

  wireContactForm()
})

onBeforeUnmount(() => {
  const stage = stageRef.value
  stage?.removeEventListener('pointermove', onPointerMove)
  stage?.removeEventListener('pointerleave', onPointerLeave)
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style>
/* ===================== لوحة ألوان زرقاء ===================== */
:root{
  --bg-color: #0b1220;
  --second-bg-color: #0f1b2d;
  --surface: #13233a;
  --text-color: #e6f0ff;
  --muted: #a9c3ff;
  --main-color: #5aa8ff;
  --main-strong: #498cff;
  --accent: #7de1ff;

  /* ألوان حلقات داكنة ووهّاجة */
  --ring-1: rgba(0, 164, 255, 0.55);
  --ring-2: rgba(0, 104, 255, 0.50);
  --ring-glow: rgba(0, 164, 255, 0.35);
}
:root.light-mode{
  --bg-color:#eaf3ff;
  --second-bg-color:#dceaff;
  --surface:#ffffff;
  --text-color:#0e2440;
  --muted:#335c99;
  --main-color:#2f7bff;
  --main-strong:#1f5ed1;
  --accent:#009dff;

  --ring-1: rgba(0, 132, 255, 0.55);
  --ring-2: rgba(0, 92, 255, 0.52);
  --ring-glow: rgba(0, 140, 255, 0.40);
}

/* أساسيات */
*{margin:0;padding:0;box-sizing:border-box}
html, body {
  /* منع سكرول أفقي ناتج عن عناصر خلفية أو صور متجاوزة */
  overflow-x: hidden;
}
html{font-size:62.5%}
body{background:var(--bg-color);color:var(--text-color);font-family: 'Lalezar', sans-serif}
section{min-height:100vh;padding:10rem 9% 2rem;scroll-margin-top:90px}

/* ===================== NAV ===================== */
.header{position:fixed;inset-inline:0;top:0;z-index:100;transition:.3s}
.header.sticky{backdrop-filter:saturate(1.2) blur(8px)}
.nav-polished{background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,0))}
.nav-wrap{
  max-width:1200px;margin-inline:auto;padding:1.2rem 2rem;
  display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:1rem;;margin-top: 1rem
}
.brand{display:inline-flex;align-items:center;gap:.8rem;text-decoration:none;margin-right: 2rem;}
.brand i{font-size:2.4rem;color:var(--main-color) }
.logo{font-size:2.5rem;font-weight:700;color:var(--text-color)}
.nav-links{display:flex;align-items:center;gap:2rem;justify-self:center}
.nav-links a{
  position:relative;font-size:1.8rem;color:var(--text-color);opacity:.9;text-decoration:none;font-weight:700;
}
.nav-links a::after{
  content:"";position:absolute;left:0;right:0;bottom:-6px;height:2px;
  background:linear-gradient(90deg,var(--accent),var(--main-color));
  transform:scaleX(0);transform-origin:100% 50%;transition:transform .25s ease
}
.nav-links a:hover::after,
.nav-links a.is-active::after{transform:scaleX(1);transform-origin:0 50%}
.nav-links a:hover{opacity:1}
.nav-actions{justify-self:end;display:flex;align-items:center;gap:.2rem}
.theme-btn{
  display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.18);
  background:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.02));
  color:var(--text-color);border-radius:.8rem;padding:.6rem 1rem;font-size:1.3rem;cursor:pointer;
  transition:transform .1s ease, border-color .2s, background .2s
}
.theme-btn:hover{transform:translateY(-1px);border-color:rgba(255,255,255,.35)}
.auth-link{
  color:var(--text-color);text-decoration:none;font-size:1.4rem;padding:.6rem 1rem;border-radius:.7rem;
  transition:color .2s, background .2s;opacity:.9
}
.auth-link:hover{opacity:1;background:rgba(255,255,255,.06)}
.auth-cta{
  text-decoration:none;color:#fff;background:linear-gradient(135deg,var(--main-strong),var(--main-color));
  padding:.8rem 1.2rem;border-radius:.9rem;font-weight:700;font-size:1.35rem;
  box-shadow:0 10px 24px rgba(47,123,255,.25);transition:transform .12s ease, box-shadow .2s ease
}
.auth-cta:hover{transform:translateY(-1px);box-shadow:0 16px 34px rgba(47,123,255,.35)}

/* ===================== HERO ===================== */
.hero-blue{
  position:relative;
  background:
    radial-gradient(1200px 520px at 10% -10%, rgba(90,168,255,.18), transparent 60%),
    radial-gradient(900px 400px at 100% 20%, rgba(0,157,255,.18), transparent 60%),
    var(--bg-color);
}
.hero-blue::before,
.hero-blue::after{
  content:"";position:absolute;inset:0;z-index:0;pointer-events:none;
  border:3px solid var(--ring-1);border-radius:60% / 35%;opacity:.13;transform:rotate(-8deg);
  box-shadow:0 0 18px var(--ring-glow), 0 0 42px rgba(0, 80, 255, .18), inset 0 0 22px rgba(0, 140, 255, .12);
  animation-timing-function: linear; animation-iteration-count: infinite;
  will-change: transform, opacity, box-shadow;
}
.hero-blue::after{ border-color: var(--ring-2); opacity:.12; transform:rotate(-8deg) scale(1.02); }

.animate,.map-grid{pointer-events:none}

/* ترتيب الهيرو */
.home{position:relative;display:flex;align-items:center}
.home-content{max-width:60rem;z-index:2;margin-left:auto}
.home-content h1{font-size:3.5rem;font-weight:700;line-height:1.35}
.home-content .text-animate{position:relative;width:23rem }
.home-content .text-animate h3{
  font-size:4rem;font-weight:7rem;color:transparent;-webkit-text-stroke:.7px var(--main-color);
  background-image:linear-gradient(var(--main-color),var(--main-color));background-repeat:no-repeat;-webkit-background-clip:text;
  background-position:-33rem 0
}
.home.show-animate .home-content .text-animate h3{animation:homeBgText 6s linear infinite;animation-delay:1.2s}
.home-content .text-animate h3::before{content:"";position:absolute;inset-block:0;left:0;width:0;border-right:2px solid var(--main-color)}
.home.home.show-animate .home-content .text-animate h3::before{animation:homeCursorText 6s linear infinite;animation-delay:1.2s}
.home-content p{font-size:1.6rem;line-height:2.6rem;margin:2rem 0 3.2rem;color:var(--muted)}
.btn-box{position:relative;display:flex;gap:1rem}
.btn-box .btn{
  display:inline-flex;align-items:center;justify-content:center;padding:1.1rem 1.6rem;background:var(--main-strong);
  border:.2rem solid var(--main-strong);border-radius:.9rem;color:#fff;font-size:1.8rem;font-weight:700;position:relative;overflow:hidden;transition:.4s;
  box-shadow:0 10px 26px rgba(31,94,209,.32)
}
.btn-box .btn:hover{filter:saturate(1.06);transform:translateY(-1px)}
.btn-box .btn::before{
  content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent);
  transform:translateX(-120%);transition:transform .6s ease
}
.btn-box .btn:hover::before{transform:translateX(120%)}

/* ========== إضافات التأثيرات (pulse, shimmer, pop) - ضع بعد قواعد .btn الأساسية ========== */

/* Pulse: تكبير بسيط وظل */
.btn-box .btn.pulse {
  transition: transform 0.25s ease, box-shadow 0.25s ease, color 0.25s ease, filter 0.25s ease;
  will-change: transform, box-shadow;
}
.btn-box .btn.pulse:hover {
  transform: translateY(-4px) scale(1.03);
  box-shadow: 0 12px 34px rgba(31,94,209,0.28);
}

/* Shimmer: خط لمعان مائل يتحرك فوق الزر */
.btn-box .btn.shimmer {
  position: relative;
  overflow: hidden; /* ضروري لاحتواء ::after */
}

/* shimmer strip */
.btn-box .btn.shimmer::after {
  content: "";
  position: absolute;
  top: -60%;
  left: -80%;
  width: 60%;
  height: 220%;
  transform: rotate(22deg) translateX(-100%);
  background: linear-gradient(
    to bottom,
    rgba(255,255,255,0) 0%,
    rgba(255,255,255,0.28) 45%,
    rgba(255,255,255,0) 100%
  );
  z-index: 2; /* فوق الـ ::before */
  transition: transform 0.9s ease;
  pointer-events: none;
  mix-blend-mode: screen;
  filter: blur(0.4px);
}

/* عند المرور يتحرك الشيمر عبر الزر */
.btn-box .btn.shimmer:hover::after {
  transform: rotate(22deg) translateX(260%);
}

/* Pop: تكبير قصير مع ارتداد خفيف */
.btn-box .btn.pop {
  transition: transform 0.18s cubic-bezier(.2,.9,.2,1), box-shadow 0.18s ease;
  will-change: transform, box-shadow;
}
.btn-box .btn.pop:hover {
  transform: translateY(-6px) scale(1.07);
  box-shadow: 0 18px 46px rgba(31,94,209,0.30);
}

/* Safety & perf tweaks */
.btn-box .btn,
.btn-box .btn::before,
.btn-box .btn::after {
  backface-visibility: hidden;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* ===================== باقي الكود كما كان (لم أغير الباقي) ===================== */

/* الشعار */
.home-imgHover{
  position:absolute;top:0;left:0;width:45%;max-width:45%;height:100%;z-index:1;pointer-events:none;perspective:900px;
  overflow:hidden; /* منع تجاوز الصورة للحاوية */
}
.home-imgHover::before{display:none!important;}
.home-imgHover img:not(.spin-move){
  width:100%;height:100%;object-fit:contain!important;
  filter:drop-shadow(0 14px 32px rgba(31,94,209,.28)) saturate(1.03) contrast(1.03)!important;
  transform:none!important;animation:none!important;backface-visibility:visible;
}
:where(.home) :where(.home-imgHover) img.spin-move{
  width:100%;height:100%;object-fit:contain!important;filter:drop-shadow(0 14px 32px rgba(31,94,209,.28)) saturate(1.03) contrast(1.03)!important;
  transform-origin:center center;transform-style: preserve-3d;backface-visibility: visible;will-change: transform;
  animation: spinY 14s linear infinite !important;
  max-width:100%;max-height:100%;
}
.home:hover .home-imgHover img.spin-move{ animation-duration: 9s !important; }

/* ===================== ABOUT ===================== */
.about{display:flex;flex-direction:column;align-items:center;gap:2rem;background:var(--second-bg-color);padding-bottom:4rem}
.heading{font-size:4.2rem;margin-bottom:2.2rem;text-align:center}
span{color:var(--accent)}
.about-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:2rem;width:100%;max-width:1100px}
.about-card{
  position:relative;background:linear-gradient(135deg, rgba(47,123,255,.28), rgba(0,157,255,.24));
  border:1px solid rgba(125,225,255,.30);border-radius:1.2rem;padding:2.4rem;min-height:26rem;
  box-shadow:0 18px 40px rgba(2,8,23,.28);opacity:0;will-change: transform, opacity;transform:translateY(22px);
}
.about-card h3{font-size:2rem;margin-bottom:.6rem}
.about-card p{font-size:1.5rem;color:#cfe0ff}
.about.show-animate .about-card.in-view{ animation: revealUp .6s ease forwards; }
.about.show-animate .about-card:nth-child(2).in-view,
.about.show-animate .about-card:nth-child(4).in-view{ transform:translateX(26px); animation: revealRight .6s ease forwards; }
.about.show-animate .about-card:nth-child(1).in-view{ animation-delay:.00s; }
.about.show-animate .about-card:nth-child(2).in-view{ animation-delay:.08s; }
.about.show-animate .about-card:nth-child(3).in-view{ animation-delay:.16s; }
.about.show-animate .about-card:nth-child(4).in-view{ animation-delay:.24s; }
.about-card:hover{ transform:translateY(-4px); box-shadow:0 26px 54px rgba(2,8,23,.32); }
@media (max-width:1000px){ .about-grid{grid-template-columns:repeat(2,minmax(0,1fr))} }
@media (max-width:520px){ .about-grid{grid-template-columns:1fr} }

/* ===================== SHOWCASE ===================== */
.showcase{min-height:auto;padding-bottom:7rem;background:var(--second-bg-color)}
.showcase .heading{margin-bottom:2rem}
.sc-tabs{display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap;margin:0 auto 1.6rem}
.sc-tab{
  border:1px solid rgba(125,225,255,.22);
  background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.02));
  color:var(--text-color);padding:.7rem 1.1rem;border-radius:.9rem;cursor:pointer;
  font-size:1.35rem;transition:transform .12s ease, border-color .2s, background .2s
}
.sc-tab:hover{transform:translateY(-1px)}
.sc-tab.active{
  background:linear-gradient(135deg,var(--main-strong),var(--main-color));
  border-color:transparent;color:#fff;box-shadow:0 12px 26px rgba(47,123,255,.28)
}
.sc-stage{perspective:1200px;max-width:980px;margin:1.4rem auto 1rem;padding:1rem}
.device{
  --rx: 0deg; --ry: 0deg; --shine: 0deg;
  position:relative;margin:0 auto;width:min(92vw,940px);aspect-ratio:16/9;border-radius:1.6rem;overflow:hidden;
  border:1px solid rgba(125,225,255,.22);
  background:linear-gradient(180deg,rgba(255,255,255,.04),rgba(255,255,255,.02));
  box-shadow:0 24px 64px rgba(2,8,23,.35), inset 0 0 0 1px rgba(255,255,255,.04);
  transform: rotateX(var(--rx)) rotateY(var(--ry));transform-style: preserve-3d;transition: transform .15s ease-out;
}
.device .screen{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:saturate(1.02);transform: translateZ(30px);}
.device .shine{
  position:absolute;inset:-40% -40%;pointer-events:none;
  background: conic-gradient(from var(--shine), rgba(255,255,255,.18), transparent 20% 80%, rgba(255,255,255,.18));
  mix-blend-mode: overlay;opacity:.35;transform: translateZ(60px);
}
.badge{
  position:absolute;padding:.5rem .8rem;font-weight:700;font-size:1.2rem;border-radius:1rem;
  background:rgba(0,157,255,.16);color:#e6f0ff;border:1px solid rgba(125,225,255,.25);backdrop-filter: blur(6px);
  animation: floatY 4.5s ease-in-out infinite;transform: translateZ(80px);
}
.badge.b1{ top:8%; left:8%; animation-delay:0s }
.badge.b2{ bottom:12%; right:10%; animation-delay:.6s }
.badge.b3{ top:18%; right:22%; animation-delay:1.2s }
@keyframes floatY{ 0%,100%{ transform: translateZ(80px) translateY(0) } 50%{ transform: translateZ(80px) translateY(-8px) } }
.sc-caption{ text-align:center; max-width:860px; margin:1rem auto 0; color:#cfe0ff; font-size:1.55rem; line-height:2.4rem }
.sc-thumbs{display:flex;gap:.8rem;justify-content:center;margin-top:1.2rem;flex-wrap:wrap}
.thumb{
  width:92px;aspect-ratio:16/10;border-radius:.8rem;overflow:hidden;cursor:pointer;border:1px solid rgba(125,225,255,.22);
  padding:0;background:transparent;transition: transform .12s ease, box-shadow .2s ease;
}
.thumb img{width:100%;height:100%;object-fit:cover}
.thumb:hover{transform: translateY(-2px)}
.thumb.active{box-shadow:0 10px 24px rgba(47,123,255,.28)}
.showcase.show-animate .device{ animation: scReveal .7s ease forwards; opacity:1 }
@keyframes scReveal{ 0%{opacity:0;transform: translateY(22px) rotateX(6deg) rotateY(-6deg)} 100%{opacity:1;transform: rotateX(var(--rx)) rotateY(var(--ry))} }
@media (max-width:768px){ .badge{display:none} }

/* ===================== Features (Clean Grid) ===================== */
.features.clean{
  min-height:auto;
  padding-bottom:7rem;
  background:var(--second-bg-color);
}
.feat-grid{
  max-width:1100px;
  margin: 1.2rem auto 0;
  display:grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap:1.4rem;
}
.feat{
  display:flex; gap:1.2rem; align-items:flex-start;
  background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
  border:1px solid rgba(125,225,255,.22);
  border-radius:1.1rem; padding:1.4rem 1.6rem;
  box-shadow: 0 14px 30px rgba(2,8,23,.22), inset 0 0 0 1px rgba(255,255,255,.03);
  transition: transform .12s ease, box-shadow .2s ease, border-color .2s ease;
}
.feat:hover{
  transform: translateY(-3px);
  box-shadow:0 20px 44px rgba(2,8,23,.28);
  border-color: rgba(125,225,255,.35);
}
.feat-ico{
  width:42px; height:42px; flex: 0 0 42px;
  display:inline-flex; align-items:center; justify-content:center;
  border-radius:10px;
  background: linear-gradient(135deg,var(--main-strong),var(--main-color));
  color:#fff; font-size:1.8rem;
  box-shadow:0 10px 24px rgba(47,123,255,.30);
}
.feat h4{
  font-size:1.7rem; margin:.1rem 0 .3rem 0; color:var(--text-color);
}
.feat p{
  font-size:1.35rem; color:#cfe0ff; line-height:2.2rem;
}
@media (max-width: 992px){
  .feat-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); }
}
@media (max-width: 560px){
  .feat-grid{ grid-template-columns: 1fr; }
}

/* ===================== TEAM ===================== */
.team-sec{min-height:auto;padding-bottom:7rem;background:var(--second-bg-color)}
.team-row{display:flex;gap:1.6rem;justify-content:center;align-items:stretch;max-width:1100px;margin:0 auto;flex-wrap:nowrap}
.team-box{width:100%;max-width:340px;background:var(--surface);border:1px solid rgba(125,225,255,.18);border-radius:1.4rem;padding:1.6rem 1.6rem 2rem;text-align:center;box-shadow:0 16px 36px rgba(2,8,23,.25)}
.about-img{position:relative;border-radius:50%;display:flex;justify-content:center;align-items:center}
.about-img.small{width:14rem;height:14rem;margin-inline:auto}
.about-img.small img{width:90%;height:90%;object-fit:cover;border-radius:50%;border:.2rem solid var(--accent)}
.about-img .circle-spin{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(0);width:100%;height:100%;border-radius:50%;border-top:.2rem solid rgba(255,255,255,.08);border-bottom:.2rem solid rgba(255,255,255,.08);border-left:.2rem solid var(--accent);border-right:.2rem solid var(--accent);animation:aboutSpinner 8s linear infinite}
.team-name{font-size:1.9rem;margin-top:1.2rem}
.team-role{font-size:1.4rem;color:var(--main-color);margin-top:.1rem}
.team-bio{font-size:1.35rem;margin-top:.6rem;color:#cfe0ff;line-height:2.2rem}
@media (max-width:1100px){ .team-row{flex-wrap:wrap} }

/* ===================== CONTACT ===================== */
.contact .heading{
  position: static !important;
  left: auto !important;
  transform: none !important;
  display: block !important;
  text-align: center !important;
  margin-bottom: 2.2rem !important;
  color: var(--text-color);
}
.contact form{max-width:70rem;margin:0 auto;text-align:center}
.contact form .input-box{display:flex;justify-content:space-between;flex-wrap:wrap}
.contact form .input-box .input-field{width:49%;margin:.8rem 0;position:relative}
.contact input, .contact textarea{
  width:100%;padding:1.5rem;font-size:1.6rem;color:var(--text-color);background:transparent;border-radius:.8rem;border:.2rem solid rgba(125,225,255,.35)
}
.contact input::placeholder,.contact textarea::placeholder{color:#a9c3ff}
.contact .focus{position:absolute;inset:0;width:0;height:100%;border-radius:.8rem;background:linear-gradient(135deg, rgba(47,123,255,.25), rgba(0,157,255,.22));z-index:-1;transition:.45s}
.input-field input:focus~.focus, .input-field input:valid~.focus, .textarea-field textarea:focus~.focus, .textarea-field textarea:valid~.focus{width:100%}
.textarea-field{position:relative;margin:.8rem 0 2.7rem;display:flex}
.btn-box.btns .btn{cursor:pointer}

/* ===================== FOOTER ===================== */
.footer{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;padding:2rem 9%;background:var(--second-bg-color)}
.footer-text p{font-size:1.4rem;color:#cfe0ff}
.footer-iconTop a{display:inline-flex;align-items:center;justify-content:center;padding:.8rem;background:var(--main-strong);border:.2rem solid var(--main-strong);border-radius:.8rem;color:#fff}

/* ===================== Animations ===================== */
@keyframes homeBgText{0%,10%,100%{background-position:-23rem 0}65%,85%{background-position:0 0}}
@keyframes homeCursorText{0%,10%,100%{width:0}65%,78%,85%{width:100%;opacity:1}75%,81%{opacity:0}}
@keyframes aboutSpinner{100%{transform:translate(-50%,-50%) rotate(360deg)}}
.hero-blue::before{ animation-name: ringSpin, ringPulse; animation-duration: 34s, 5s; }
.hero-blue::after  { animation-name: ringSpinReverse, ringPulse; animation-duration: 46s, 6s; }
.hero-blue:hover::before{ animation-duration: 18s, 4s; }
.hero-blue:hover::after { animation-duration: 26s, 5s; }
@keyframes ringSpin{ 0%{transform: rotate(0deg) scale(1)} 50%{transform: rotate(180deg) scale(1.02)} 100%{transform: rotate(360deg) scale(1)} }
@keyframes ringSpinReverse{ 0%{transform: rotate(0deg) scale(1.02)} 100%{transform: rotate(-360deg) scale(1.02)} }
@keyframes ringPulse{ 0%,100%{opacity:.12; box-shadow:0 0 14px var(--ring-glow), 0 0 34px rgba(0,80,255,.16), inset 0 0 18px rgba(0,140,255,.10)} 50%{opacity:.18; box-shadow:0 0 22px var(--ring-glow), 0 0 48px rgba(0,80,255,.22), inset 0 0 26px rgba(0,140,255,.16)} }
@keyframes revealUp{ 0%{opacity:0; transform:translateY(22px)} 100%{opacity:1; transform:translateY(0)} }
@keyframes revealRight{ 0%{opacity:0; transform:translateX(26px)} 100%{opacity:1; transform:translateX(0)} }
@keyframes spinY{ 0%{ transform: rotateY(0deg) } 100%{ transform: rotateY(360deg) } }

*{ -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; }

/* ===================== Fix clicks ===================== */
.animate,.map-grid,.hero-blue::before,.hero-blue::after{pointer-events:none!important}
.header{z-index:100!important}

/* ===================== Responsive ===================== */
@media (max-width:1200px){ html{font-size:56%} }
@media (max-width:991px){ section{padding:9rem 5% 2rem} }
@media (max-width:768px){ .nav-links{display:none} }
@media (max-width:520px){
  html{font-size:50%}
  .team-row{flex-direction:column;align-items:center}
  .team-box{max-width:92%}
}

/* احترام تقليل الحركة */
@media (prefers-reduced-motion: reduce){
  :where(.home) :where(.home-imgHover) img.spin-move{ animation:none !important; }
  .about-card{ animation:none !important; transform:none !important; opacity:1 !important; }
  .showcase .device{ animation:none !important; }
}

</style>
