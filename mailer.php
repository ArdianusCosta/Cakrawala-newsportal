<?php
header('X-Content-Type-Options: nosniff');
$sent = null;
$statusMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil & bersihkan input
    $sender_name   = trim(preg_replace('/[\r\n]+/', ' ', $_POST['sender-name'] ?? ''));
    $sender_mail   = trim($_POST['sender-mail'] ?? '');
    $receiver_mail = trim($_POST['receiver-mail'] ?? '');
    $subject       = trim(preg_replace('/[\r\n]+/', ' ', $_POST['subject'] ?? ''));
    $message       = trim($_POST['message'] ?? '');

    // validasi dasar
    if (!filter_var($sender_mail, FILTER_VALIDATE_EMAIL)) {
        $statusMsg = 'Email pengirim tidak valid.';
    } elseif (!filter_var($receiver_mail, FILTER_VALIDATE_EMAIL)) {
        $statusMsg = 'Email penerima tidak valid.';
    } elseif ($sender_mail === $receiver_mail) {
        $statusMsg = 'Pengirim dan penerima tidak boleh sama.';
    } elseif ($sender_name === '' || $subject === '' || $message === '') {
        $statusMsg = 'Semua field wajib diisi.';
    } else {
        // header aman
        $headers  = "From: {$sender_name} <{$sender_mail}>\r\n";
        $headers .= "Reply-To: {$sender_mail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $sent = mail($receiver_mail, $subject, $message, $headers);
        $statusMsg = $sent ? 'Email berhasil dikirim.' : 'Gagal mengirim email. Cek konfigurasi mail server.';
    }

    // kalau request AJAX, kirim JSON dan stop
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'sent' => (bool)$sent,
            'message' => $statusMsg
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Prank</title>
    <link rel="icon" href="https://img.icons8.com/?size=48&id=X0mEIh0RyDdL&format=png" type="image/png">
    <style>
body{background:#f4f4f4;font-family:Arial,sans-serif;margin:0;padding:0}#title{text-align:center;font-size:2em;margin:30px 10px 10px;font-weight:bolder}.container{width:80%;margin:0 auto;padding:20px;background:#fff;box-shadow:0 0 10px rgba(0,0,0,.05);border-radius:5px}.input-group{margin:10px 0;display:flex;flex-direction:column}.input-control{padding:10px;border:1px solid #ddd;border-radius:5px;margin:5px 0;font-size:1em;outline-color:#8ab6f9}textarea.input-control{resize:vertical;min-height:120px} #dialog{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);display:none;justify-content:center;align-items:center}#dialog-content{width:90%;max-width:400px;background:#fff;padding:20px;border-radius:5px;text-align:center}#dialog-title{font-size:1.5em;font-weight:bolder;margin:10px 0}#dialog-close{padding:10px 20px;background:#e03a3a;color:#fff;border:none;border-radius:5px;cursor:pointer;margin-top:10px}#send:disabled{background:#ddd;color:#aaa;cursor:not-allowed}@media(min-width:1100px){.container{width:50%}}
    </style>
</head>
<body>
    <div id="title">Email Prank</div>
    <form class="container" id="mail" method="post">
        <div class="input-group">
            <label>Nama Pengirim*</label>
            <input type="text" class="input-control" name="sender-name" placeholder="Masukkan nama pengirim" required>
        </div>
        <div class="input-group">
            <label>Email Pengirim*</label>
            <input type="email" class="input-control" name="sender-mail" placeholder="pengirim@email.com" required>
        </div>
        <div class="input-group">
            <label>Email Penerima*</label>
            <input type="email" class="input-control" name="receiver-mail" placeholder="penerima@email.com" required>
        </div>
        <div class="input-group">
            <label>Subjek*</label>
            <input type="text" class="input-control" name="subject" placeholder="Judul email" required>
        </div>
        <div class="input-group">
            <label>Pesan*</label>
            <textarea class="input-control" name="message" placeholder="Isi pesan" required></textarea>
        </div>
        <div class="input-group">
            <input type="submit" id="send" class="input-control" value="Kirim Email">
        </div>
    </form>

    <div id="dialog">
        <div id="dialog-content">
            <div id="dialog-title"></div>
            <div id="dialog-message"></div>
            <button id="dialog-close">Tutup</button>
        </div>
    </div>

<script>
const form = document.getElementById('mail');
const dialog = document.getElementById('dialog');
const title = document.getElementById('dialog-title');
const msg = document.getElementById('dialog-message');
const sendBtn = document.getElementById('send');

form.addEventListener('submit', function(e){
    e.preventDefault();
    sendBtn.disabled = true;
    sendBtn.value = 'Mengirim...';

    const data = new FormData(form);
    fetch('', { method: 'POST', body: data, headers: {'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json())
    .then(res => {
        title.textContent = res.sent ? 'Berhasil Terkirim' : 'Gagal';
        title.style.color = res.sent ? 'green' : '#e03a3a';
        msg.textContent = res.message;
        dialog.style.display = 'flex';
    })
    .catch(() => {
        title.textContent = 'Error';
        title.style.color = '#e03a3a';
        msg.textContent = 'Terjadi kesalahan jaringan.';
        dialog.style.display = 'flex';
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.value = 'Kirim Email';
    });
});

document.getElementById('dialog-close').onclick = () => dialog.style.display = 'none';
dialog.onclick = (e) => { if(e.target === dialog) dialog.style.display = 'none'; };
</script>
</body>
</html>