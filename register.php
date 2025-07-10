<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">
    <!-- title -->
    <title>สมัครสมาชิก</title>
    <!-- link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="wrapper" id="content">
        <main>
            <div class="container">
                <div class="row justify-content-center mx-1 mt-5">
                    <div class="text-center mb-2">
                        <h1>สมัครสมาชิก</h1>
                    </div>
                    <div class="bg-white border rounded shadow-sm p-3 mb-3" style="max-width: 414px;">
                        <form id="form_register">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <div class="mb-3">
                                <label class="form-label mb-2" for="email">อีเมล / Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-2" for="password">รหัสผ่าน / Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="****" minlength="4">
                                <span class="text-muted opacity-75" style="font-size: 12px;"><i class="fa-solid fa-circle-exclamation"></i> รหัสผ่านต้อง 4 ตัวขึ้นไป</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-2" for="confirm_password">ยืนยันรหัสผ่าน / Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="****" minlength="4">
                                <span class="text-muted opacity-75" style="font-size: 12px;"><i class="fa-solid fa-circle-exclamation"></i> รหัสผ่านต้องสอดคล้องกัน</span>
                            </div>
                            <button id="submit" type="submit" class="btn btn-danger w-100">
                                <span id="btn-text">สมัครสมาชิก</span>
                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                    <div class="text-center">
                        <p class="text-muted m-0">มีบัญชีอยู่แล้ว? <span><a href="index.php">เข้าสู่ระบบ</a></span></p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // form register
            const formRegister = document.getElementById('form_register');
            formRegister.addEventListener('submit', function(e) {

                e.preventDefault();
                const formData = new FormData(formRegister);
                const submitBtn = document.getElementById('submit');
                const spinner = document.getElementById('spinner');
                const btnText = document.getElementById('btn-text');

                spinner.classList.remove('d-none');
                btnText.innerText = '';
                submitBtn.disabled = true;

                setTimeout(() => {
                    axios.post('backend/register_db.php', formData)
                        .then(response => {
                            const data = response.data;

                            if (data.status == "success") {
                                Swal.fire({
                                    title: "สำเร็จ",
                                    text: data.msg,
                                    icon: "success",
                                    confirmButtonText: 'ตกลง',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "เกิดข้อผิดพลาด",
                                    text: data.msg,
                                    icon: "error",
                                    confirmButtonText: 'ตกลง',
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: "เกิดข้อผิดพลาด",
                                text: "ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง",
                                icon: "error",
                                confirmButtonText: 'ตกลง',
                                timer: 2000,
                                timerProgressBar: true
                            });
                        })
                        .finally(() => {
                            spinner.classList.add('d-none');
                            btnText.innerText = 'สมัครสมาชิก';
                            submitBtn.disabled = false;
                        });
                }, 500);
            });
        });
    </script>

</body>

</html>