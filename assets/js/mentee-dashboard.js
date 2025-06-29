// Courses JavaScript File
const courseDetailModal = document.getElementById('course-detail-modal');

window.appUtils = window.appUtils || {};
window.appUtils.openModal = function(modalElement) {
  if (modalElement) {
    modalElement.classList.add('open');
  }
};

// DOM Elements
const coursesContainer = document.getElementById('courses-container');
const enrolledCoursesContainer = document.getElementById('enrolled-courses-container');
const courseFilters = {
  category: document.getElementById('category-filter'),
  level: document.getElementById('level-filter'),
  price: document.getElementById('price-filter')
};

// Course data (in a real app, this would come from an API)
const courses = [
  {
    id: 1,
    title: 'Dasar-Dasar Desain UI/UX',
    description: 'Pelajari prinsip-prinsip dasar desain antarmuka pengguna dan pengalaman pengguna untuk membangun aplikasi yang intuitif dan user-friendly.',
    image: 'https://images.pexels.com/photos/326501/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'design',
    level: 'beginner',
    price: 'free',
    priceAmount: 0,
    duration: '6 jam',
    lessons: 18,
    students: 1284,
    rating: 4.7,
    instructor: {
      name: 'Budi Santoso',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
      bio: 'UI/UX Designer dengan pengalaman 7 tahun di industri teknologi. Alumnus ITS dengan spesialisasi desain interaksi.'
    },
    progress: 0,
    modules: [
      {
        title: 'Pengenalan UI/UX',
        lessons: [
          { title: 'Apa itu UI dan UX?', duration: '8 menit', type: 'video', locked: false },
          { title: 'Prinsip Dasar Desain', duration: '12 menit', type: 'video', locked: false },
          { title: 'Pentingnya User Research', duration: '15 menit', type: 'video', locked: false }
        ]
      },
      {
        title: 'Wireframing & Prototyping',
        lessons: [
          { title: 'Dasar-Dasar Wireframing', duration: '10 menit', type: 'video', locked: false },
          { title: 'Tools untuk Prototyping', duration: '14 menit', type: 'video', locked: false },
          { title: 'Praktik: Membuat Wireframe Sederhana', duration: '25 menit', type: 'practice', locked: false }
        ]
      }
    ],
    comments: [
      {
        author: 'Dewi Putri',
        avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
        date: '2023-10-02',
        content: 'Kursus yang sangat informatif! Saya baru memulai belajar UI/UX dan materi dasarnya sangat mudah dipahami.',
        likes: 8,
        role: 'student',
        replies: [
          {
            author: 'Budi Santoso',
            avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
            date: '2023-10-03',
            content: 'Terima kasih Dewi! Senang mendengar kursus ini bermanfaat untukmu.',
            likes: 2,
            role: 'mentor'
          }
        ]
      }
    ]
  },
  {
    id: 2,
    title: 'Pemrograman Web Fullstack dengan JavaScript',
    description: 'Menjadi developer web fullstack dengan menguasai JavaScript, Node.js, React, dan MongoDB untuk membangun aplikasi web modern end-to-end.',
    image: 'https://images.pexels.com/photos/614117/pexels-photo-614117.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'programming',
    level: 'intermediate',
    price: 'premium',
    priceAmount: 750000,
    duration: '24 jam',
    lessons: 72,
    students: 856,
    rating: 4.9,
    instructor: {
      name: 'Hadi Prasetyo',
      avatar: 'https://randomuser.me/api/portraits/men/22.jpg',
      bio: 'Full-stack Developer dengan 10+ tahun pengalaman. Kontributor aktif di proyek open source dan mentor teknologi.'
    },
    progress: 0,
    modules: [
      {
        title: 'Dasar-Dasar JavaScript',
        lessons: [
          { title: 'Pengenalan JavaScript', duration: '12 menit', type: 'video', locked: false },
          { title: 'Variabel, Tipe Data, dan Operator', duration: '18 menit', type: 'video', locked: false },
          { title: 'Fungsi dan Pemrograman Fungsional', duration: '24 menit', type: 'video', locked: false },
          { title: 'Latihan: Membuat Program Sederhana', duration: '30 menit', type: 'practice', locked: true }
        ]
      },
      {
        title: 'Front-end dengan React',
        lessons: [
          { title: 'Pengenalan React', duration: '15 menit', type: 'video', locked: true },
          { title: 'Membuat Komponen React', duration: '22 menit', type: 'video', locked: true },
          { title: 'State dan Props', duration: '20 menit', type: 'video', locked: true },
          { title: 'Proyek: Membangun Todo App', duration: '45 menit', type: 'project', locked: true }
        ]
      }
    ],
    comments: [
      {
        author: 'Ahmad Rizki',
        avatar: 'https://randomuser.me/api/portraits/men/54.jpg',
        date: '2023-09-15',
        content: 'Kursus ini benar-benar mengubah karir saya! Saya sekarang bekerja sebagai full-stack developer di sebuah startup teknologi.',
        likes: 24,
        role: 'student',
        replies: [
          {
            author: 'Hadi Prasetyo',
            avatar: 'https://randomuser.me/api/portraits/men/22.jpg',
            date: '2023-09-16',
            content: 'Wah, selamat Ahmad! Sangat senang mendengar kesuksesanmu.',
            likes: 5,
            role: 'mentor'
          }
        ]
      }
    ]
  },
  {
    id: 3,
    title: 'Digital Marketing Fundamental',
    description: 'Pelajari strategi pemasaran digital yang efektif, termasuk SEO, media sosial, content marketing, dan analitik untuk bisnis online.',
    image: 'https://images.pexels.com/photos/905163/pexels-photo-905163.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'marketing',
    level: 'beginner',
    price: 'free',
    priceAmount: 0,
    duration: '8 jam',
    lessons: 22,
    students: 2145,
    rating: 4.6,
    instructor: {
      name: 'Sinta Wijaya',
      avatar: 'https://randomuser.me/api/portraits/women/12.jpg',
      bio: 'Digital Marketing Strategist dengan pengalaman 8 tahun di berbagai industri. Spesialis dalam kampanye multi-channel.'
    },
    progress: 0,
    modules: [
      {
        title: 'Pengenalan Digital Marketing',
        lessons: [
          { title: 'Apa itu Digital Marketing?', duration: '10 menit', type: 'video', locked: false },
          { title: 'Saluran Pemasaran Digital', duration: '15 menit', type: 'video', locked: false },
          { title: 'Membuat Strategi Digital Marketing', duration: '20 menit', type: 'video', locked: false }
        ]
      },
      {
        title: 'Search Engine Optimization (SEO)',
        lessons: [
          { title: 'Dasar-Dasar SEO', duration: '12 menit', type: 'video', locked: false },
          { title: 'Keyword Research', duration: '18 menit', type: 'video', locked: false },
          { title: 'On-Page & Off-Page SEO', duration: '22 menit', type: 'video', locked: false },
          { title: 'Praktik: Mengoptimalkan Halaman Web', duration: '30 menit', type: 'practice', locked: false }
        ]
      }
    ],
    comments: []
  },
  {
    id: 4,
    title: 'Startup 101: Membangun Bisnis Digital',
    description: 'Panduan komprehensif untuk memulai dan mengembangkan startup digital, dari validasi ide hingga pitching ke investor.',
    image: 'https://images.pexels.com/photos/6476260/pexels-photo-6476260.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'business',
    level: 'intermediate',
    price: 'premium',
    priceAmount: 850000,
    duration: '12 jam',
    lessons: 32,
    students: 678,
    rating: 4.8,
    instructor: {
      name: 'Reza Firmansyah',
      avatar: 'https://randomuser.me/api/portraits/men/42.jpg',
      bio: 'Serial entrepreneur dengan 3 exit sukses. Angel investor dan mentor untuk startup teknologi di Asia Tenggara.'
    },
    progress: 0,
    modules: [
      {
        title: 'Validasi Ide Bisnis',
        lessons: [
          { title: 'Menemukan Masalah yang Layak Dipecahkan', duration: '15 menit', type: 'video', locked: false },
          { title: 'Customer Development', duration: '20 menit', type: 'video', locked: false },
          { title: 'Validasi dengan MVP', duration: '25 menit', type: 'video', locked: true },
          { title: 'Studi Kasus: Startup Sukses Indonesia', duration: '30 menit', type: 'video', locked: true }
        ]
      },
      {
        title: 'Fundraising & Pitching',
        lessons: [
          { title: 'Jenis-Jenis Pendanaan', duration: '18 menit', type: 'video', locked: true },
          { title: 'Membangun Pitch Deck', duration: '22 menit', type: 'video', locked: true },
          { title: 'Teknik Pitching yang Efektif', duration: '28 menit', type: 'video', locked: true },
          { title: 'Praktik: Membuat Pitch Deck', duration: '40 menit', type: 'project', locked: true }
        ]
      }
    ],
    comments: []
  },
  {
    id: 5,
    title: 'Fotografi Dasar untuk Pemula',
    description: 'Pelajari teknik fotografi dasar, penggunaan kamera DSLR, komposisi, pencahayaan, dan editing foto untuk menghasilkan karya visual yang mengesankan.',
    image: 'https://images.pexels.com/photos/1571076/pexels-photo-1571076.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'design',
    level: 'beginner',
    price: 'free',
    priceAmount: 0,
    duration: '7 jam',
    lessons: 20,
    students: 1876,
    rating: 4.7,
    instructor: {
      name: 'Maya Adiningsih',
      avatar: 'https://randomuser.me/api/portraits/women/22.jpg',
      bio: 'Fotografer profesional dengan 12 tahun pengalaman. Karyanya telah dipublikasikan di National Geographic dan majalah internasional lainnya.'
    },
    progress: 0,
    modules: [
      {
        title: 'Pengenalan Fotografi',
        lessons: [
          { title: 'Memahami Kamera DSLR', duration: '15 menit', type: 'video', locked: false },
          { title: 'Exposure Triangle: ISO, Aperture, Shutter Speed', duration: '25 menit', type: 'video', locked: false },
          { title: 'Lensa dan Fungsinya', duration: '18 menit', type: 'video', locked: false }
        ]
      },
      {
        title: 'Teknik Komposisi',
        lessons: [
          { title: 'Rule of Thirds', duration: '12 menit', type: 'video', locked: false },
          { title: 'Leading Lines & Framing', duration: '14 menit', type: 'video', locked: false },
          { title: 'Praktik: Foto Outdoor', duration: '30 menit', type: 'practice', locked: false }
        ]
      }
    ],
    comments: []
  },
  {
    id: 6,
    title: 'Machine Learning untuk Bisnis',
    description: 'Implementasi machine learning untuk solusi bisnis praktis, termasuk analisis prediktif, segmentasi pelanggan, dan sistem rekomendasi.',
    image: 'https://images.pexels.com/photos/8386440/pexels-photo-8386440.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    category: 'programming',
    level: 'advanced',
    price: 'premium',
    priceAmount: 1200000,
    duration: '18 jam',
    lessons: 45,
    students: 432,
    rating: 4.9,
    instructor: {
      name: 'Dr. Irfan Wibowo',
      avatar: 'https://randomuser.me/api/portraits/men/36.jpg',
      bio: 'Ph.D dalam Machine Learning dari NTU Singapore. Peneliti dan praktisi ML dengan fokus pada aplikasi bisnis dan AI.'
    },
    progress: 0,
    modules: [
      {
        title: 'Dasar-Dasar Machine Learning',
        lessons: [
          { title: 'Pengenalan ML untuk Bisnis', duration: '20 menit', type: 'video', locked: false },
          { title: 'Supervised vs Unsupervised Learning', duration: '25 menit', type: 'video', locked: false },
          { title: 'Data Preprocessing', duration: '30 menit', type: 'video', locked: true },
          { title: 'Feature Engineering', duration: '35 menit', type: 'video', locked: true }
        ]
      },
      {
        title: 'Implementasi Model Bisnis',
        lessons: [
          { title: 'Customer Segmentation', duration: '28 menit', type: 'video', locked: true },
          { title: 'Analisis Prediktif untuk Penjualan', duration: '32 menit', type: 'video', locked: true },
          { title: 'Sistem Rekomendasi Produk', duration: '40 menit', type: 'video', locked: true },
          { title: 'Proyek: Membangun Customer Churn Predictor', duration: '60 menit', type: 'project', locked: true }
        ]
      }
    ],
    comments: []
  }
];

// Enrolled courses (in a real app, this would come from user data)
const enrolledCourses = [];

// Initialize the courses functionality
document.addEventListener('DOMContentLoaded', function() {
  // Render initial courses
  renderCourses(courses);
  
  // Initialize course filters
  initFilters();
  
  // Initialize course detail modal
  initCourseDetails();
});

// Render courses
function renderCourses(coursesToRender) {
  if (!coursesContainer) return;
  
  // Clear the container
  coursesContainer.innerHTML = '';
  
  if (coursesToRender.length === 0) {
    coursesContainer.innerHTML = `
      <div class="empty-state">
        <img src="img/empty-search.svg" alt="Tidak ada kursus">
        <h3>Tidak ada kursus yang ditemukan</h3>
        <p>Silakan coba filter lain atau reset filter untuk melihat semua kursus</p>
        <button class="btn btn-primary" id="reset-filters">Reset Filter</button>
      </div>
    `;
    
    document.getElementById('reset-filters').addEventListener('click', function() {
      resetFilters();
    });
    
    return;
  }
  
  // Create course cards
  coursesToRender.forEach(course => {
    const courseCard = createCourseCard(course);
    coursesContainer.appendChild(courseCard);
  });
  
  // Add event listeners to course cards
  addCourseCardListeners();
}

// Create course card
function createCourseCard(course) {
  const courseCard = document.createElement('div');
  courseCard.className = 'course-card';
  courseCard.setAttribute('data-course-id', course.id);
  
  courseCard.innerHTML = `
    <div class="course-image">
      <img src="${course.image}" alt="${course.title}">
      <div class="course-badges">
        <span class="badge badge-level">${getLevelText(course.level)}</span>
        <span class="badge badge-price ${course.price}">${getPriceText(course.price)}</span>
      </div>
    </div>
    <div class="course-content">
      <h3 class="course-title">${course.title}</h3>
      <p class="course-description">${course.description}</p>
      <div class="course-meta">
        <div class="meta-item">
          <i class="fas fa-clock"></i>
          <span>${course.duration}</span>
        </div>
        <div class="meta-item">
          <i class="fas fa-film"></i>
          <span>${course.lessons} Pelajaran</span>
        </div>
      </div>
      <div class="course-footer">
        <div class="instructor">
          <img src="${course.instructor.avatar}" alt="${course.instructor.name}" class="instructor-avatar">
          <span class="instructor-name">${course.instructor.name}</span>
        </div>
        <div class="course-price ${course.price}">${getPriceText(course.price)}</div>
      </div>
    </div>
  `;
  
  return courseCard;
}

// Add event listeners to course cards
function addCourseCardListeners() {
  const courseCards = document.querySelectorAll('.course-card');

  courseCards.forEach(card => {
    card.addEventListener('click', function () {
      const courseId = this.getAttribute('data-course-id');
      const selectedCourse = courses.find(course => course.id == courseId);

      // ⬇️ Di sinilah kamu tambahkan log untuk debug
      console.log("Klik card dengan ID:", courseId, selectedCourse);

      if (selectedCourse) {
        showCourseDetails(selectedCourse);
      } else {
        console.warn("Course tidak ditemukan untuk ID:", courseId);
      }
    });
  });
}


// Show course details in modal
function showCourseDetails(course) {
  // Update modal content
  document.getElementById('modal-course-title').textContent = course.title;
  document.getElementById('modal-course-image').src = course.image;
  document.getElementById('modal-course-level').textContent = getLevelText(course.level);
  document.getElementById('modal-course-price').textContent = getPriceText(course.price);
  document.getElementById('modal-course-price').className = `badge badge-price ${course.price}`;
  document.getElementById('modal-course-duration').textContent = course.duration;
  document.getElementById('modal-course-lessons').textContent = `${course.lessons} Pelajaran`;
  document.getElementById('modal-course-students').textContent = `${course.students.toLocaleString()} Siswa`;
  document.getElementById('modal-course-rating').textContent = course.rating;
  document.getElementById('modal-instructor-avatar').src = course.instructor.avatar;
  document.getElementById('modal-instructor-name').textContent = course.instructor.name;
  document.getElementById('modal-course-description').textContent = course.description;
  
  // Update instructor support modal
  document.getElementById('support-instructor-avatar').src = course.instructor.avatar;
  document.getElementById('support-instructor-name').textContent = course.instructor.name;
  document.getElementById('support-instructor-bio').textContent = course.instructor.bio;
  
  // Update enroll button based on price
  const enrollBtn = document.getElementById('enroll-btn');
  if (course.price === 'free') {
    enrollBtn.textContent = 'Ikuti Kursus Gratis';
  } else {
    enrollBtn.textContent = `Beli Kursus (${window.appUtils.formatCurrency(course.priceAmount)})`;
  }
  
  // Render modules
  renderModules(course);
  
  // Render comments
  renderComments(course);
  
  // Open the modal
  window.appUtils.openModal(courseDetailModal);
  
  // Add event listener to enroll button
  enrollBtn.onclick = function() {
    enrollInCourse(course);
  };
}

// Render modules in course detail
function renderModules(course) {
  const modulesContainer = document.getElementById('modal-course-modules');
  
  if (!modulesContainer) return;
  
  // Clear the container
  modulesContainer.innerHTML = '';
  
  // Create modules
  course.modules.forEach((module, index) => {
    const moduleEl = document.createElement('div');
    moduleEl.className = 'module';
    if (index === 0) moduleEl.classList.add('open');
    
    // Module header
    const moduleHeader = document.createElement('div');
    moduleHeader.className = 'module-header';
    moduleHeader.innerHTML = `
      <div class="module-title">
        <i class="fas fa-chevron-right"></i>
        ${module.title}
      </div>
      <div class="module-meta">
        <span>${module.lessons.length} Pelajaran</span>
      </div>
    `;
    
    // Module content
    const moduleContent = document.createElement('div');
    moduleContent.className = 'module-content';
    
    // Create lessons
    module.lessons.forEach(lesson => {
      const lessonEl = document.createElement('div');
      lessonEl.className = `lesson ${lesson.locked ? 'lesson-locked' : ''}`;
      
      let icon;
      switch (lesson.type) {
        case 'video':
          icon = 'fa-play-circle';
          break;
        case 'practice':
          icon = 'fa-laptop-code';
          break;
        case 'project':
          icon = 'fa-project-diagram';
          break;
        default:
          icon = 'fa-file-alt';
      }
      
      lessonEl.innerHTML = `
        <div class="lesson-icon">
          <i class="fas ${icon}"></i>
        </div>
        <div class="lesson-title">${lesson.title}</div>
        <div class="lesson-duration">${lesson.duration}</div>
        ${lesson.locked ? '<div class="lesson-lock"><i class="fas fa-lock"></i></div>' : ''}
      `;
      
      moduleContent.appendChild(lessonEl);
    });
    
    moduleEl.appendChild(moduleHeader);
    moduleEl.appendChild(moduleContent);
    modulesContainer.appendChild(moduleEl);
    
    // Add click event to module header
    moduleHeader.addEventListener('click', function() {
      moduleEl.classList.toggle('open');
    });
  });
}

// Render comments in course detail
function renderComments(course) {
  const commentsContainer = document.getElementById('modal-comments-list');
  
  if (!commentsContainer) return;
  
  // Clear the container
  commentsContainer.innerHTML = '';
  
  if (course.comments.length === 0) {
    commentsContainer.innerHTML = `
      <div class="empty-forum">
        <h3>Belum ada diskusi</h3>
        <p>Jadilah yang pertama memulai diskusi tentang kursus ini</p>
      </div>
    `;
    return;
  }
  
  // Create comments
  course.comments.forEach(comment => {
    const commentEl = document.createElement('div');
    commentEl.className = 'comment';
    
    commentEl.innerHTML = `
      <img src="${comment.avatar}" alt="${comment.author}" class="comment-avatar">
      <div class="comment-body">
        <div class="comment-header">
          <div class="comment-info">
            <div class="comment-author">${comment.author} <span class="comment-role ${comment.role}">${getRoleText(comment.role)}</span></div>
            <div class="comment-meta">${window.appUtils.formatDate(comment.date)}</div>
          </div>
          <div class="comment-actions">
            <span class="comment-action">Laporkan</span>
          </div>
        </div>
        <div class="comment-content">${comment.content}</div>
        <div class="comment-footer">
          <span class="comment-reaction ${comment.liked ? 'active' : ''}">
            <i class="far fa-thumbs-up"></i> ${comment.likes}
          </span>
          <span class="comment-reaction">
            <i class="far fa-comment"></i> Balas
          </span>
        </div>
        
        ${comment.replies && comment.replies.length > 0 ? `
          <div class="reply-list">
            ${comment.replies.map(reply => `
              <div class="comment">
                <img src="${reply.avatar}" alt="${reply.author}" class="comment-avatar">
                <div class="comment-body">
                  <div class="comment-header">
                    <div class="comment-info">
                      <div class="comment-author">${reply.author} <span class="comment-role ${reply.role}">${getRoleText(reply.role)}</span></div>
                      <div class="comment-meta">${window.appUtils.formatDate(reply.date)}</div>
                    </div>
                  </div>
                  <div class="comment-content">${reply.content}</div>
                  <div class="comment-footer">
                    <span class="comment-reaction ${reply.liked ? 'active' : ''}">
                      <i class="far fa-thumbs-up"></i> ${reply.likes}
                    </span>
                  </div>
                </div>
              </div>
            `).join('')}
          </div>
        ` : ''}
      </div>
    `;
    
    commentsContainer.appendChild(commentEl);
  });
}

// Initialize course filters
function initFilters() {
  // Add event listeners to filter dropdowns
  Object.values(courseFilters).forEach(filter => {
    if (filter) {
      filter.addEventListener('change', function() {
        applyFilters();
      });
    }
  });
}

// Apply filters to courses
function applyFilters() {
  const categoryFilter = courseFilters.category ? courseFilters.category.value : 'all';
  const levelFilter = courseFilters.level ? courseFilters.level.value : 'all';
  const priceFilter = courseFilters.price ? courseFilters.price.value : 'all';
  
  const filteredCourses = courses.filter(course => {
    return (categoryFilter === 'all' || course.category === categoryFilter) &&
           (levelFilter === 'all' || course.level === levelFilter) &&
           (priceFilter === 'all' || course.price === priceFilter);
  });
  
  renderCourses(filteredCourses);
}

// Reset filters
function resetFilters() {
  Object.values(courseFilters).forEach(filter => {
    if (filter) {
      filter.value = 'all';
    }
  });
  
  renderCourses(courses);
}

// Initialize course detail functionality
function initCourseDetails() {
  // Tabs in course detail
  const courseTabLinks = document.querySelectorAll('.course-tab-link');
  const courseTabContents = document.querySelectorAll('.course-tab-content');
  
  courseTabLinks.forEach(tabLink => {
    tabLink.addEventListener('click', function() {
      // Remove active class from all tabs
      courseTabLinks.forEach(tab => tab.classList.remove('active'));
      
      // Add active class to current tab
      this.classList.add('active');
      
      // Hide all tab content
      courseTabContents.forEach(content => content.classList.remove('active'));
      
      // Show the corresponding tab content
      const tabId = this.getAttribute('data-tab');
      document.getElementById(tabId + '-content').classList.add('active');
    });
  });
}

// Close modal on close button click
document.querySelectorAll('.modal-close').forEach(btn => {
  btn.addEventListener('click', function () {
    const modal = btn.closest('.modal');
    window.appUtils.closeModal(modal);
  });
});


// Enroll in a course
function enrollInCourse(course) {
  // In a real app, this would involve API calls, payment processing, etc.
  // For this demo, we'll just add the course to the enrolled courses
  
  if (course.price === 'premium') {
    // Show payment modal or redirect to payment page
    alert('Redirecting to payment page for premium course...');
    return;
  }
  
  // Check if already enrolled
  if (enrolledCourses.some(c => c.id === course.id)) {
    alert('Anda sudah terdaftar dalam kursus ini');
    return;
  }
  
  // Add to enrolled courses
  const enrolledCourse = {...course, progress: 0, lastActivity: new Date()};
  enrolledCourses.push(enrolledCourse);
  
  // Close the modal
  window.appUtils.closeModal(courseDetailModal);
  
  // Switch to My Courses tab
  document.querySelector('.tab-link[data-tab="my-courses"]').click();
  
  // Render enrolled courses
  renderEnrolledCourses();
  
  // Show success message
  alert('Berhasil mendaftar kursus!');
}

// Render enrolled courses
function renderEnrolledCourses() {
  if (!enrolledCoursesContainer) return;
  
  // Clear the container
  enrolledCoursesContainer.innerHTML = '';
  
  if (enrolledCourses.length === 0) {
    enrolledCoursesContainer.innerHTML = `
      <div class="empty-state">
        <img src="img/empty-courses.svg" alt="Belum ada kursus">
        <h3>Anda belum mengikuti kursus apapun</h3>
        <p>Jelajahi katalog kurs
us kami dan mulai perjalanan belajar Anda</p>
        <button class="btn btn-primary">Jelajahi Kursus</button>
      </div>
    `;
    return;
  }
  
  // Create enrolled course cards
  enrolledCourses.forEach(course => {
    const courseCard = document.createElement('div');
    courseCard.className = 'enrolled-course-card';
    
    courseCard.innerHTML = `
      <div class="enrolled-course-content">
        <div class="enrolled-header">
          <div class="enrolled-image">
            <img src="${course.image}" alt="${course.title}">
          </div>
          <div class="enrolled-info">
            <h3 class="enrolled-title">${course.title}</h3>
            <div class="enrolled-meta">
              <div class="meta-item">
                <i class="fas fa-clock"></i>
                <span>${course.duration}</span>
              </div>
              <div class="meta-item">
                <i class="fas fa-film"></i>
                <span>${course.lessons} Pelajaran</span>
              </div>
            </div>
            <div class="progress-wrapper">
              <div class="progress-bar">
                <div class="progress-value" style="width: ${course.progress}%"></div>
              </div>
              <div class="progress-text">
                <span>Progress: ${course.progress}%</span>
                <span>${Math.round(course.progress / 100 * course.lessons)} / ${course.lessons} pelajaran</span>
              </div>
            </div>
          </div>
        </div>
        <div class="enrolled-footer">
          <button class="btn btn-primary continue-btn">Lanjutkan Belajar</button>
          <div class="last-activity">Aktivitas terakhir: ${window.appUtils.formatDate(course.lastActivity)}</div>
        </div>
      </div>
    `;
    
    enrolledCoursesContainer.appendChild(courseCard);
  });
}

// Helper functions
function getLevelText(level) {
  switch (level) {
    case 'beginner':
      return 'Pemula';
    case 'intermediate':
      return 'Menengah';
    case 'advanced':
      return 'Mahir';
    default:
      return level;
  }
}

function getPriceText(price) {
  switch (price) {
    case 'free':
      return 'Gratis';
    case 'premium':
      return 'Premium';
    default:
      return price;
  }
}

function getRoleText(role) {
  switch (role) {
    case 'student':
      return 'Siswa';
    case 'mentor':
      return 'Mentor';
    case 'admin':
      return 'Admin';
    default:
      return role;
  }
}

window.appUtils = {
  openModal(modal) {
    modal.classList.add('open');
  },
  closeModal(modal) {
    modal.classList.remove('open');
  },
  formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric', month: 'long', year: 'numeric'
    });
  },
  formatCurrency(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
  }
};
