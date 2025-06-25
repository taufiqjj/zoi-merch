<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['delete_id']);

    // Ambil gambar
    $stmt = $koneksi->prepare("SELECT gambar FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($gambarPath);
    $stmt->fetch();
    $stmt->close();

    // Hapus gambar dari folder
    if ($gambarPath && file_exists("../" . $gambarPath)) {
        unlink("../" . $gambarPath);
    }

    // Hapus dari DB
    $stmt = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_nama = trim($_POST['edit_nama']);
    $uploadDir = '../image/product/';

    $newImagePath = null;

    if (!empty($_FILES['edit_gambar']['name'])) {
        $gambar = $_FILES['edit_gambar'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));
        $filename = uniqid('product_') . '.' . $ext;

        if (in_array($ext, $allowedExt)) {
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
                $newImagePath = 'image/product/' . $filename;
            }
        }
    }

    if ($newImagePath) {
        $stmt = $koneksi->prepare("UPDATE produk SET nama = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param("ssi", $edit_nama, $newImagePath, $edit_id);
    } else {
        $stmt = $koneksi->prepare("UPDATE produk SET nama = ? WHERE id = ?");
        $stmt->bind_param("si", $edit_nama, $edit_id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: index.php?updated=1");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $gambar = $_FILES['gambar'];

    if ($nama && $gambar['error'] === 0) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));
        $filename = uniqid('product_') . '.' . $ext;

        if (in_array($ext, $allowedExt)) {
            $uploadDir = '../image/product/';
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
                $dbPath = 'image/product/' . $filename;
                $stmt = $koneksi->prepare("INSERT INTO produk (nama, gambar) VALUES (?, ?)");
                $stmt->bind_param("ss", $nama, $dbPath);
                $stmt->execute();
                header("Location: index.php?success=1");
                exit;
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Ekstensi file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        }
    } else {
        $error = "Nama produk dan gambar harus diisi.";
    }
}

// Ambil data produk dari database
$produkList = [];
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = $koneksi->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produkList[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php
    // Simulasi data user (dalam aplikasi nyata, ambil dari database/session)
    $user = [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150'
    ];

    // Menu sidebar
    $menuItems = [
        ['icon' => 'fas fa-box', 'label' => 'Produk', 'url' => 'index.php', 'active' => true],
        ['icon' => 'fas fa-star', 'label' => 'Ulasan', 'url' => 'ulasan.php', 'active' => false],
    ];
    ?>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gray-900 text-white w-64 min-h-screen flex flex-col transition-all duration-300 ease-in-out">
            <!-- Logo -->
            <div class="flex items-center justify-center py-6 px-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <span class="text-xl font-bold">Zoi Merch</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2">
                    <?php foreach ($menuItems as $item): ?>
                        <li>
                            <a href="<?= $item['url'] ?>"
                                class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-colors duration-200 hover:bg-gray-800 <?= $item['active'] ? 'bg-blue-600 text-white' : 'text-gray-300' ?>">
                                <i class="<?= $item['icon'] ?> w-5"></i>
                                <span><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <!-- User Profile -->
            <div class="px-4 py-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <img src="<?= $user['avatar'] ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?= $user['name'] ?></p>
                        <p class="text-xs text-gray-400 truncate"><?= $user['email'] ?></p>
                    </div>
                    <a href="logout.php" class="text-gray-400 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 id="pageTitle" class="text-2xl font-semibold text-gray-900">Produk</h1>
                    </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Tabel Produk -->
                <div class="bg-white p-6 shadow">
                    <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>

                    <?php if (!empty($error)): ?>
                        <div class="bg-red-100 text-red-800 p-2 rounded mb-4"><?= $error ?></div>
                    <?php elseif (isset($_GET['success'])): ?>
                        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">Produk berhasil ditambahkan.</div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" name="nama" class="w-full border border-gray-300 p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                            <input type="file" name="gambar" accept="image/*" class="w-full border border-gray-300 p-2 rounded" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"><i class="fas fa-plus mr-2"></i>Tambah Produk</button>
                    </form>
                </div>
                <div id="produk-content" class="bg-white rounded-xl shadow-sm border border-gray-200 mt-4">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($produkList as $produk): ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="../<?= $produk['gambar'] ?>" alt="<?= $produk['nama'] ?>"
                                                class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= $produk['nama'] ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-2">
                                                <button
                                                    class="text-blue-600 hover:text-blue-900 transition"
                                                    onclick="openEditModal(<?= $produk['id'] ?>, '<?= htmlspecialchars($produk['nama'], ENT_QUOTES) ?>', '<?= $produk['gambar'] ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    onclick="confirmDelete(<?= $produk['id'] ?>)"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200 text-sm font-medium">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
    <!-- Modal Edit Produk -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">
            <h2 class="text-xl font-semibold mb-4">Edit Produk</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_id" id="edit_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="edit_nama" id="edit_nama" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Gambar (opsional)</label>
                    <input type="file" name="edit_gambar" accept="image/*" class="w-full border border-gray-300 p-2 rounded">
                    <img id="edit_preview" class="mt-2 w-20 h-20 object-cover rounded hidden" alt="Preview">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    <button type="submit" name="edit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
            <button class="absolute top-2 right-3 text-gray-600 hover:text-gray-900" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus produk ini?</p>
            <form method="POST">
                <input type="hidden" name="delete_id" id="delete_id">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit" name="delete" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, gambar) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            const preview = document.getElementById('edit_preview');

            if (gambar) {
                preview.src = '../' + gambar;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Make sidebar responsive
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Initialize responsive sidebar
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }

        // Menu item click handlers
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Only prevent default for internal navigation
                if (this.getAttribute('href') === 'index.php') {
                    e.preventDefault();
                }

                // Remove active class from all menu items
                document.querySelectorAll('nav a').forEach(item => {
                    item.classList.remove('bg-blue-600', 'text-white');
                    item.classList.add('text-gray-300');
                });

                // Add active class to clicked item
                this.classList.add('bg-blue-600', 'text-white');
                this.classList.remove('text-gray-300');

                // Update page title
                const pageTitle = this.querySelector('span').textContent;
                document.getElementById('pageTitle').textContent = pageTitle;

                // Close mobile sidebar
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>