<h1>Register</h1>
<form method="post" action="<?= site_url('auth/register/') ?>" autocomplete="off">
    <div>
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
    <div>
        <label for="role">Daftar sebagai:</label>
        <select id="role" name="role" required>
            <option value="">-- Pilih --</option>
            <option value="outlet">Outlet</option>
            <option value="cust">Customer</option>
        </select>
        </div>

    <div></div>
        <button type="submit">Register</button>
    </div>
</form>
