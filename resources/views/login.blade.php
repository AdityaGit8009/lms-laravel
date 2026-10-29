<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Handshake | Secure Access — SPI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
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
        <div class="tag-dot"></div>India's Premier EdTech Platform
      </div>
      <h2 class="htitle"><em>Handshake</em><br>Secure Access</h2>
      <p class="hdesc">Join 200,000+ learners transforming their careers. Industry-ready, mentor-led and outcome-focused learning built with performance, privacy and accessibility at the core.</p>

      <div class="feats">
        <div class="feat">
          <div class="feat-ico"><svg viewBox="0 0 24 24">
              <polyline points="16 18 22 12 16 6" />
              <polyline points="8 6 2 12 8 18" />
            </svg></div>
          <div class="feat-lbl">Practical &<br>Hands-on Learning</div>
          <div class="feat-sub">Real projects,<br>real outcomes</div>
        </div>
        <div class="feat">
          <div class="feat-ico"><svg viewBox="0 0 24 24">
              <path d="M18 20V10" />
              <path d="M12 20V4" />
              <path d="M6 20v-6" />
            </svg></div>
          <div class="feat-lbl">Growth<br>Dashboards</div>
          <div class="feat-sub">Track every<br>milestone</div>
        </div>
        <div class="feat">
          <div class="feat-ico"><svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="3" />
              <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14" />
            </svg></div>
          <div class="feat-lbl">Easy<br>Integrations</div>
          <div class="feat-sub">Seamless tools<br>ecosystem</div>
        </div>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="stat-num">200K+</div>
          <div class="stat-lbl">Active Learners</div>
        </div>
        <div class="sdiv"></div>
        <div class="stat">
          <div class="stat-num">98%</div>
          <div class="stat-lbl">Placement Rate</div>
        </div>
        <div class="sdiv"></div>
        <div class="stat">
          <div class="stat-num">500+</div>
          <div class="stat-lbl">Courses</div>
        </div>
      </div>
    </section>

    <section class="panel">
      <div class="card">
        <div class="card-head">
          <div class="card-ico">
            <svg viewBox="0 0 24 24">
              <path d="M20.5 14.5l-2.5 2.5-3-3-1 1 3 3-2 2-3-3-1 1 3 3-1.5 1.5a2.12 2.12 0 0 1-3 0L6 19l-.35.35A2 2 0 0 1 2.83 16.5L11 8.38l1.62 1.62L14 8.62 10.5 5H8L3.5 2l-1 1L6 6.5v2l5.38 5.38 1-1-4.38-4.38 5.5-5.5H15l4.5 4.5z" />
            </svg>
          </div>
          <h3>Welcome Back</h3>
          <p>Sign in to your Handshake account</p>
        </div>

        <div class="steps">
          <div class="step" id="s1"></div>
          <div class="step off" id="s2"></div>
          <div class="step off" id="s3"></div>
        </div>

        @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="loginForm">
          @csrf

          <div class="fg">
            <label class="lbl" for="email">Email Address</label>
            <div class="iw">
              <span class="i-ico">
                <svg viewBox="0 0 24 24">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
              </span>
              <input class="inp" id="email" name="email" type="email" placeholder="you@example.com" value="{{ old('email') }}" autocomplete="email" required>
            </div>
            <p class="ferr" id="error_email"></p>
          </div>

          <div class="fg">
            <label class="lbl" for="password">Password</label>
            <div class="iw">
              <span class="i-ico">
                <svg viewBox="0 0 24 24">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
              </span>
              <input class="inp" id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required>
              <button class="eye-btn" type="button" onclick="togglePwd()" aria-label="Toggle password">
                <svg id="ei" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
            <p class="ferr" id="error_password"></p>
          </div>

          <div class="opts">
            <label class="rem">
              <input type="checkbox" name="remember" id="remember">
              <span>Remember me</span>
            </label>
            <a class="fgot" href="#">Forgot password?</a>
          </div>

          <div id="loginMessage"></div>

          <button class="btn" type="submit">
            <svg viewBox="0 0 24 24">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
              <polyline points="10 17 15 12 10 7" />
              <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            Sign In Securely
          </button>
        </form>

        <div class="or">or</div>
        <p class="sig">Don't have an account yet? <a href="{{ route('register') }}">Create Account</a></p>

        <div class="trust">
          <div class="trust-item">
            <svg viewBox="0 0 24 24">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>SSL Secured
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>256-bit Encrypted
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 24 24">
              <polyline points="20 6 9 17 4 12" />
            </svg>GDPR Compliant
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="ft">
      <div class="fc">
        <div class="fc-ttl">
          <svg viewBox="0 0 24 24">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>Head Office
        </div>
        <div class="fc-addr"><strong>Softpro Tower</strong><br>Near New Hanuman Temple, Kapoorthala<br>Aliganj, Lucknow (UP) – 226036</div>
      </div>

      <div class="fc">
        <div class="fc-ttl">
          <svg viewBox="0 0 24 24">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg>Help & Support
        </div>
        <div class="fc-row">
          <svg viewBox="0 0 24 24">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
            <polyline points="22,6 12,13 2,6" />
          </svg>polyprepspi@gmail.com
        </div>
        <div class="fc-row">
          <svg viewBox="0 0 24 24">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg>+91 70801 02007
        </div>
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
      const eye = document.getElementById('ei');

      if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = `
          <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.8 21.8 0 0 1 5.06-6.94"></path>
          <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-3.22 4.31"></path>
          <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
          <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
      } else {
        input.type = 'password';
        eye.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        `;
      }
    }

    $(document).ready(function() {
      $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        $('#loginMessage').html('');
        $('.ferr').html('');

        $.ajax({
          url: "{{ route('api.login') }}",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
          },

          success: function(response) {
            // console.log(response);
            // localStorage.setItem("auth_token", response);
            $('#loginMessage').html('<div class="alert alert-success">' + response.message + '</div>');
            setTimeout(function() {
              window.location.href = response.redirect;
            }, 800);
          },
          error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              $.each(xhr.responseJSON.errors, function(field, messages) {
                $('#error_' + field).html(messages[0]);
              });
              return;
            }

            let msg = 'Login failed.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }

            $('#loginMessage').html('<div class="alert alert-error">' + msg + '</div>');
          }
        });
      });
    });
  </script>

</body>

</html>