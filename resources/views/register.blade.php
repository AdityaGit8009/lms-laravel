<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Handshake | Student Registration — SPI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

</head>

<body>

  <header>
    <a class="logo" href="{{ url('/') }}">
      <div class="logo-mark">SPi</div>
      <div class="logo-info">
        <span>Softpro India</span>
        <span>Computer Technologies</span>
      </div>
    </a>

    <div class="hdr-center">
      <strong>Handshake</strong>
      <span>Powered by Softpro India Computer Technologies (P) Ltd</span>
    </div>

    <div class="secure-pill">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <rect x="3" y="11" width="18" height="11" rx="2" />
        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
      </svg>
      Secure Access
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="hero-ring1"></div>
      <div class="hero-ring2"></div>

      <div class="tag">
        <div class="tag-dot"></div>Industry-ready Learning Platform
      </div>

      <h2 class="htitle"><em>Handshake</em><br>Student Registration</h2>
      <p class="hdesc">Begin your learning journey with industry-ready courses. Learn. Grow. Achieve. Register once and unlock a digital-first training experience powered by experts.</p>

      <div class="feats">
        <div class="feat">
          <div class="feat-ico"><i class="bi bi-book"></i></div>
          <div class="feat-lbl">Learn with<br>Experts</div>
          <div class="feat-sub">Mentor-led and<br>practical sessions</div>
        </div>

        <div class="feat">
          <div class="feat-ico"><i class="bi bi-laptop"></i></div>
          <div class="feat-lbl">Digital-first<br>Experience</div>
          <div class="feat-sub">Fast, modern and<br>guided onboarding</div>
        </div>

        <div class="feat">
          <div class="feat-ico"><i class="bi bi-patch-check"></i></div>
          <div class="feat-lbl">Get<br>Certified</div>
          <div class="feat-sub">Progress with trusted<br>credentials</div>
        </div>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="stat-num">200K+</div>
          <div class="stat-lbl">Learners</div>
        </div>
        <div class="sdiv"></div>
        <div class="stat">
          <div class="stat-num">500+</div>
          <div class="stat-lbl">Programs</div>
        </div>
        <div class="sdiv"></div>
        <div class="stat">
          <div class="stat-num">98%</div>
          <div class="stat-lbl">Success Focus</div>
        </div>
      </div>
    </section>

    <section class="panel">
      <div class="card">
        <div class="card-head">
          <div class="card-ico">
            <i class="bi bi-person-plus-fill"></i>
          </div>
          <h3>Register Now</h3>
          <p>Create your Handshake student account</p>
        </div>

        <div class="steps">
          <div class="step" id="s1"></div>
          <div class="step" id="s2"></div>
          <div class="step off" id="s3"></div>
        </div>

        @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="studentForm" enctype="multipart/form-data">
          @csrf
          <div class="scroll-area">

            <div class="fg">
              <label class="lbl" for="name">Name</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-person"></i></span>
                <input class="inp" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name" pattern="^[A-Za-z\s]{3,50}$" title="Name should only contain letters and spaces (3–50 characters)" required>
              </div>
              <p class="ferr" id="error_name"></p>
            </div>

            <div class="fg">
              <label class="lbl" for="college">College Name</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-building"></i></span>
                <input class="inp" type="text" id="college" name="college" value="{{ old('college') }}" placeholder="Enter college name" pattern="^[A-Za-z0-9\s.,'-]{3,100}$" title="College name can include letters, numbers, and punctuation (3–100 characters)" required>
              </div>
              <p class="ferr" id="error_college"></p>
            </div>

            <div class="fg">
              <label class="lbl" for="regno">SPI Enroll ID / Mobile Number</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-card-text"></i></span>
                <input class="inp" type="text" id="regno" name="regno" value="{{ old('regno') }}" placeholder="Enter SPI Enroll ID / Mobile Number" pattern="^[A-Za-z0-9]{5,15}$" title="Registration number should be 5–15 characters (letters and digits only)" required>
              </div>
              <p class="ferr" id="error_regno"></p>
            </div>

            <div class="fg">
              <label class="lbl" for="course">Course</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-journal-bookmark"></i></span>
                <select name="course" id="course" class="sel" required>
                  <option value="">Select Course</option>
                  <option value="1" data-duration="3" {{ old('course') == '1' ? 'selected' : '' }}>Diploma</option>
                  <option value="2" data-duration="4" {{ old('course') == '2' ? 'selected' : '' }}>B.Tech</option>
                  <option value="3" data-duration="3" {{ old('course') == '3' ? 'selected' : '' }}>BCA</option>
                  <option value="4" data-duration="1" {{ old('course') == '4' ? 'selected' : '' }}>PGDCA</option>
                  <option value="5" data-duration="2" {{ old('course') == '5' ? 'selected' : '' }}>MCA</option>
                  <option value="6" data-duration="2" {{ old('course') == '6' ? 'selected' : '' }}>M.Sc</option>
                  <option value="7" data-duration="3" {{ old('course') == '7' ? 'selected' : '' }}>B.A</option>
                  <option value="8" data-duration="3" {{ old('course') == '8' ? 'selected' : '' }}>B.Sc</option>
                  <option value="9" data-duration="2" {{ old('course') == '9' ? 'selected' : '' }}>M.Tech</option>
                </select>
              </div>
              <p class="ferr" id="error_course"></p>
            </div>

            <div class="grid2">
              <div class="fg">
                <label class="lbl" for="branch">Branch</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-diagram-3"></i></span>
                  <select name="branch" id="branch" class="sel" required disabled>
                    <option value="">Select Branch</option>
                  </select>
                </div>
                <p class="ferr" id="error_branch"></p>
              </div>

              <div class="fg">
                <label class="lbl" for="year">Year</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-calendar3"></i></span>
                  <select name="year" id="year" class="sel" required disabled>
                    <option value="" selected disabled>Select Year</option>
                  </select>
                </div>
                <p class="ferr" id="error_year"></p>
              </div>
            </div>

            <div class="grid2">
              <div class="fg">
                <label class="lbl" for="mobile">Mobile</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-telephone"></i></span>
                  <input class="inp" type="text" id="mobile" name="mobile" maxlength="10" value="{{ old('mobile') }}" placeholder="Enter mobile number" pattern="^[6-9]\d{9}$" title="Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9" required>
                </div>
                <p class="ferr" id="error_mobile"></p>
              </div>

              <div class="fg">
                <label class="lbl" for="email">Email (User ID)</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-envelope"></i></span>
                  <input class="inp" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address (e.g. example@mail.com)" required>
                </div>
                <p class="ferr" id="error_email"></p>
              </div>
            </div>

            <div class="grid2">
              <div class="fg">
                <label class="lbl" for="gender">Gender</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-gender-ambiguous"></i></span>
                  <select name="gender" id="gender" class="sel" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                  </select>
                </div>
                <p class="ferr" id="error_gender"></p>
              </div>

              <div class="fg">
                <label class="lbl" for="password">Password</label>
                <div class="iw">
                  <span class="i-ico"><i class="bi bi-lock"></i></span>
                  <input class="inp" type="password" id="password" name="password" placeholder="Enter password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,20}$" title="Password must be 6–20 characters long, include at least one letter and one number" required>
                  <button class="eye-btn" type="button" onclick="togglePwd()" aria-label="Toggle password">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                  </button>
                </div>
                <p class="ferr" id="error_password"></p>
              </div>
            </div>

            <div class="fg">
              <label class="lbl" for="pro">Select Your Chosen Programme</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-mortarboard"></i></span>
                <select name="pro" id="pro" class="sel" required>
                  <option value="">Select Programme</option>
                  <option value="12" {{ old('pro') == '12' ? 'selected' : '' }}>Summer Training 2025</option>
                  <option value="13" {{ old('pro') == '13' ? 'selected' : '' }}>In House Training</option>
                  <option value="15" {{ old('pro') == '15' ? 'selected' : '' }}>Certification Programme</option>
                  <option value="16" {{ old('pro') == '16' ? 'selected' : '' }}>Virtual Internship</option>
                  <option value="8" {{ old('pro') == '8' ? 'selected' : '' }}>Apprenticeship-2025</option>
                  <option value="11" {{ old('pro') == '11' ? 'selected' : '' }}>Full Stack Development</option>
                </select>
              </div>
              <p class="ferr" id="error_pro"></p>
            </div>

            <div class="fg">
              <label class="lbl" for="location">Select Your Training Location</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-geo-alt"></i></span>
                <select name="location" id="location" class="sel" required>
                  <option value="" disabled {{ old('location') ? '' : 'selected' }}>Select Location</option>
                  <option value="House" {{ old('location') == 'House' ? 'selected' : '' }}>Softpro House, 3/213 Sector-J, Jankipuram, Lucknow - 226021</option>
                  <option value="Academy" {{ old('location') == 'Academy' ? 'selected' : '' }}>Softpro India Full Stack Academy, 1/6 Vastu Khand, Gomti Nagar, Lucknow - 226010</option>
                  <option value="Noida" {{ old('location') == 'Noida' ? 'selected' : '' }}>Softpro India Computer Technologies P Limited, Creatons Business Park, Ground Floor, H-35, Sector 63, Noida - 201301</option>
                </select>
              </div>
              <p class="ferr" id="error_location"></p>
            </div>

            <div class="fg">
              <label class="lbl" for="pic">Upload Photo</label>
              <div class="iw">
                <span class="i-ico"><i class="bi bi-upload"></i></span>
                <input class="inp" type="file" id="pic" name="pic" accept="image/*" style="padding-left:44px;padding-top:11px;padding-bottom:11px;">
              </div>
              <p class="ferr" id="error_pic"></p>
            </div>

          </div>

          <div id="registerMessage"></div>

          <div class="btn-row">
            <button class="btn" type="submit">
              <i class="bi bi-person-plus-fill"></i>
              Register Now
            </button>

            <button class="btn alt" type="reset">
              Reset
            </button>
          </div>
        </form>

        <p class="sig">Already have an account? <a href="{{ route('login') }}">Login</a></p>

        <div class="trust">
          <div class="trust-item"><i class="bi bi-shield-lock"></i>SSL Secured</div>
          <div class="trust-item"><i class="bi bi-lock"></i>256-bit Encrypted</div>
          <div class="trust-item"><i class="bi bi-check2-circle"></i>Verified Access</div>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="ft">
      <div class="fc">
        <div class="fc-ttl"><i class="bi bi-geo-alt-fill"></i>Head Office</div>
        <div class="fc-addr"><strong>Softpro Tower</strong><br>Near New Hanuman Temple, Kapoorthala<br>Aliganj, Lucknow (UP) – 226036</div>
      </div>

      <div class="fc">
        <div class="fc-ttl"><i class="bi bi-headset"></i>Help & Support</div>
        <div class="fc-row"><i class="bi bi-envelope"></i>polyprepspi@gmail.com</div>
        <div class="fc-row"><i class="bi bi-telephone"></i>+91 70801 02007</div>
        <div class="fc-hrs">Available: 10:00 AM – 07:00 PM (Mon–Sat)</div>
      </div>

      <div class="fc" style="display:flex;align-items:center;justify-content:center">
        <svg width="130" height="105" viewBox="0 0 140 110" fill="none">
          <circle cx="70" cy="55" r="48" fill="#FFF4EE" stroke="rgba(232,98,42,0.12)" stroke-width="1" />
          <circle cx="70" cy="55" r="32" fill="#FFF4EE" stroke="rgba(232,98,42,0.1)" stroke-width="1" />
          <circle cx="70" cy="55" r="16" fill="rgba(232,98,42,0.1)" stroke="rgba(232,98,42,0.25)" stroke-width="1.5" />
          <path d="M62 57.5L68 63L79 51" stroke="#E8622A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
          <text x="70" y="92" text-anchor="middle" fill="rgba(232,98,42,0.5)" font-size="9" font-family="Plus Jakarta Sans">Secure Platform</text>
        </svg>
      </div>
    </div>

    <div class="fb">© 2026 <strong>Softpro India Computer Technologies (P) Ltd.</strong> | Designed with <span class="hrt">♥</span> for Learners</div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    function togglePwd() {
      const input = document.getElementById('password');
      const icon = document.getElementById('toggleIcon');

      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye';
      } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-slash';
      }
    }

    const branchMap = {
      '1': ['Civil', 'Mechanical', 'Electrical', 'Computer Science'],
      '2': ['Computer Science', 'Information Technology', 'Civil', 'Mechanical', 'Electrical', 'Electronics'],
      '3': ['Computer Application'],
      '4': ['Computer Application'],
      '5': ['Computer Application'],
      '6': ['Computer Science', 'Mathematics', 'Physics'],
      '7': ['Arts'],
      '8': ['Physics', 'Chemistry', 'Mathematics', 'Computer Science'],
      '9': ['Computer Science', 'Civil', 'Mechanical', 'Electrical']
    };

    function populateYears(courseId) {
      const course = document.querySelector('#course option[value="' + courseId + '"]');
      const yearSelect = document.getElementById('year');

      yearSelect.innerHTML = '<option value="">Select Year</option>';

      if (!course) {
        yearSelect.disabled = true;
        return;
      }

      const duration = parseInt(course.getAttribute('data-duration')) || 0;

      for (let i = 1; i <= duration; i++) {
        let label = '';
        if (i === 1) label = 'First Year';
        else if (i === 2) label = 'Second Year';
        else if (i === 3) label = 'Third Year';
        else if (i === 4) label = 'Fourth Year';
        else label = 'Year ' + i;

        yearSelect.innerHTML += `<option value="${label}">${label}</option>`;
      }

      yearSelect.disabled = false;
    }

    function populateBranches(courseId) {
      const branchSelect = document.getElementById('branch');
      branchSelect.innerHTML = '<option value="">Select Branch</option>';

      if (!branchMap[courseId] || branchMap[courseId].length === 0) {
        branchSelect.disabled = true;
        return;
      }

      branchMap[courseId].forEach(function(branch) {
        branchSelect.innerHTML += `<option value="${branch}">${branch}</option>`;
      });

      branchSelect.disabled = false;
    }

    $(document).ready(function() {
      $('#course').on('change', function() {
        const courseId = $(this).val();
        populateBranches(courseId);
        populateYears(courseId);
      });

      const oldCourse = $('#course').val();
      const oldBranch = @json(old('branch'));
      const oldYear = @json(old('year'));

      if (oldCourse) {
        populateBranches(oldCourse);
        populateYears(oldCourse);

        if (oldBranch) {
          $('#branch').val(oldBranch);
        }

        if (oldYear) {
          $('#year').val(oldYear);
        }
      }

      $('#studentForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        $('#registerMessage').html('');
        $('.ferr').removeClass('show').html('');

        $.ajax({
          url: "{{ route('api.register') }}",
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
          },
          success: function() {
            $('#registerMessage').html('<div class="alert alert-success">Registration successful. Your account is pending admin approval.</div>');

            setTimeout(function() {
              window.location.href = "{{ route('login') }}";
            }, 1000);
          },
          error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              $.each(xhr.responseJSON.errors, function(field, messages) {
                $('#error_' + field).html(messages[0]).addClass('show');
              });
              return;
            }

            let msg = 'Registration failed. Please try again.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }

            $('#registerMessage').html('<div class="alert alert-error">' + msg + '</div>');
          }
        });
      });
    });
  </script>

</body>

</html>