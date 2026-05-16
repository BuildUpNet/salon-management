@extends('Auth.layouts.main')

@section('title')
    Login
@endsection

@section('content')
    <style>
        .otp-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .otp-modal {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
            position: relative;
        }

        .otp-close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: transparent;
            border: none;
            font-size: 22px;
            cursor: pointer;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .modal-subtext {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 95%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .otp-submit-btn {
            background-color: #4a3aff;
            color: #fff;
            padding: 12px 18px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .otp-submit-btn:hover {
            background-color: #372fc2;
            transform: translateY(-1px);
        }

        .otp-submit-btn:active {
            transform: translateY(0);
            background-color: #2d26a4;
        }


        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f7f9fc;
        }

        .login-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .login-title {
            font-size: 22px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .inputbox {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .options {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        /* Modal Styling */
        .modal.show {
            display: block;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            border-radius: 10px;
            padding: 20px;
            border: none;
        }

        #otpCountdown {
            font-size: 1.3rem;
            margin-top: 5px;
            display: block;
        }


        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            /* Dark overlay */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #ffc107;
            /* Yellow spinner to match theme */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="login-form">
            <h4>Login to SalonPOS</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-3">
                <label for="email" class="form-label"><strong>Email address</strong></label>

                <input type="text" id="login" name="login" class="form-control" placeholder="you@example.com" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <strong>Password</strong></label>

                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                    required>
            </div>

            <button type="button" class="btn login-btn mt-2" onclick="loginWithOTP()">Login</button>
         
        </div>
        
          
    </div>
<div class="loader-wrapper" id="loader" style="display: none;">
    <div class="spinner"></div>
</div>
    </div>
     
    </div>


    {{-- <!-- OTP Modal -->
    <div id="otpModal" class="otp-modal-overlay" style="display:none;" onclick="closeOtpModal(event)">
        <div class="otp-modal" onclick="event.stopPropagation()">
            <button class="otp-close-btn" onclick="closeOtpModal(event)">×</button>
            <h5 class="modal-title text-center">🔐 Verify OTP</h5>
            <p class="modal-subtext text-center">
                Enter the OTP sent to your email<br>
                <span id="otpCountdown" class="text-danger fw-bold"></span>
            </p>
            <form method="POST" action="{{ route('verify.otp') }}">
                @csrf
                <input type="hidden" name="login_id" id="login_id" value="{{ old('login_id') }}">
                <input type="text" name="otp" id="otp" class="form-control mb-3 otp-input"
                    placeholder="Enter OTP" required>
                <button type="submit" class="btn otp-submit-btn">Verify OTP</button>
            </form>
        </div>
    </div> --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        let countdownTime = 120; // 2 minutes in seconds
        let countdownInterval;

        // Show OTP modal
        function showOtpModal(loginId) {
            const modal = document.getElementById('otpModal');
            const loginInput = document.getElementById('login_id');
            const verifyBtn = document.querySelector('.otp-submit-btn');

            if (modal && loginInput) {
                loginInput.value = loginId;
                modal.style.display = 'flex';

                // Reset and start countdown
                countdownTime = 120;
                verifyBtn.disabled = false;
                startOtpCountdown();
            }
        }

        // Start countdown timer
        function startOtpCountdown() {
            clearInterval(countdownInterval); // Clear previous timer if any

            countdownInterval = setInterval(() => {
                const minutes = Math.floor(countdownTime / 60);
                const seconds = countdownTime % 60;
                const countdownEl = document.getElementById('otpCountdown');

                if (countdownEl) {
                    countdownEl.textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    if (countdownEl) countdownEl.textContent = "⛔ OTP expired";
                    document.querySelector('.otp-submit-btn').disabled = true;
                }

                countdownTime--;
            }, 1000);
        }

        // Close OTP modal
        function closeOtpModal(event) {
            event.stopPropagation();
            const modal = document.getElementById('otpModal');
            if (modal) {
                modal.style.display = 'none';
            }
            clearInterval(countdownInterval);
        }

        // async function loginWithOTP() {
        //     const login = document.getElementById('login').value;
        //     const password = document.getElementById('password').value;
        //     const loader = document.getElementById('loader');

        //     loader.style.display = 'flex'; // Show loader

        //     try {
        //         const response = await fetch('/otp-login', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //             },
        //             body: JSON.stringify({
        //                 login,
        //                 password
        //             })
        //         });

        //         const data = await response.json();

        //         if (data.success) {
        //             if (data.redirect) {
        //                 window.location.href = data.redirect;
        //             } else if (data.role === 'branch') {
        //                 showOtpModal(data.login_id);
        //             }
        //         } else {
        //             alert(data.message);
        //         }

        //     } catch (error) {
        //         console.error('Error:', error);
        //         alert('Something went wrong');
        //     } finally {
        //         loader.style.display = 'none'; // Always hide loader
        //     }
        // }
          async function loginWithOTP() {
        const login = document.getElementById('login').value;
        const password = document.getElementById('password').value;
        const loader = document.getElementById('loader');

        loader.style.display = 'flex'; // Show spinner

        try {
            const response = await fetch('/otp-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    login,
                    password
                })
            });

            const data = await response.json();

            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.role === 'branch') {
                    showOtpModal(data.login_id);
                }
            } else {
                alert(data.message || 'Login failed');
            }

        } catch (err) {
            console.error(err);
            alert('Something went wrong');
        } finally {
            loader.style.display = 'none'; // Always hide spinner
        }
    }
    </script>
@endsection
