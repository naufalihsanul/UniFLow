<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="theme-color" content="#007AFF" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <title>UniFlow - Modern Student Productivity</title>
  
  <link rel="manifest" href="manifest.json" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            apple: {
              bg: '#F2F2F7',
              card: '#FFFFFF',
              blue: '#007AFF',
              green: '#34C759',
              red: '#FF3B30',
              yellow: '#FFCC00',
              orange: '#FF9500',
              gray: '#8E8E93',
              subtle: '#E5E5EA'
            }
          },
          fontFamily: {
            sans: ['-apple-system', 'BlinkMacSystemFont', 'SF Pro Display', 'Segoe UI', 'Roboto', 'sans-serif'],
          },
          borderRadius: {
            '2xl': '16px',
            '3xl': '24px',
            '4xl': '32px'
          }
        }
      }
    }
  </script>
  <style>
    ::-webkit-scrollbar {
      width: 4px;
      height: 4px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: #D1D1D6;
      border-radius: 10px;
    }
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }
    .glass-nav {
      background: rgba(255, 255, 255, 0.90);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
    }
    .press-effect {
      transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .press-effect:active {
      transform: scale(0.96);
      opacity: 0.85;
    }
  </style>
</head>
<body class="bg-zinc-900 min-h-screen flex items-center justify-center p-0 sm:p-4 font-sans select-none antialiased">

  <!-- Mobile Frame Container (Full Screen on Phone Xiaomi 13T, Simulator Frame on Desktop) -->
  <div class="w-full max-w-full sm:max-w-[420px] h-screen sm:h-[850px] bg-apple-bg flex flex-col relative overflow-hidden sm:rounded-[48px] sm:shadow-2xl sm:border-[8px] sm:border-zinc-800">
    
    <!-- Top Status & Network Sync Header -->
    <div class="w-full pt-3 pb-1 px-5 flex justify-between items-center text-xs font-semibold text-slate-800 z-30 select-none glass border-b border-slate-200/40">
      <span id="status-bar-time">09:41</span>
      <div class="flex items-center space-x-2">
        <button onclick="fetchAppState()" title="Sinkronisasi Data" class="text-slate-500 hover:text-apple-blue press-effect flex items-center space-x-1">
          <i id="sync-icon" data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
          <span id="sync-text" class="text-[10px] text-slate-400 font-medium">MySQL</span>
        </button>
        <i data-lucide="wifi" class="w-3.5 h-3.5 text-slate-700"></i>
        <i data-lucide="battery" class="w-4 h-4 text-slate-700"></i>
      </div>
    </div>

    <!-- Dynamic Header Bar -->
    <header class="px-5 py-3 flex justify-between items-center z-20 glass border-b border-slate-200/50">
      <div>
        <h1 class="text-[10px] uppercase tracking-widest font-bold text-apple-blue flex items-center gap-1">
          <span>UniFlow</span>
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
        </h1>
        <h2 id="header-title" class="text-xl font-bold text-slate-900 leading-tight">Dashboard</h2>
      </div>
      <button id="quick-add-btn" onclick="openUniversalAddModal()" class="w-9 h-9 rounded-full bg-apple-blue text-white flex items-center justify-center shadow-md press-effect">
        <i data-lucide="plus" class="w-5 h-5"></i>
      </button>
    </header>

    <!-- Main Dynamic Content Container (Scrollable) -->
    <main id="app-content" class="flex-1 overflow-y-auto px-4 py-4 pb-24 hide-scrollbar">
      <!-- Dynamic Views Rendered via JavaScript -->
    </main>

    <!-- Bottom Navigation Bar (Apple Style Translucent) -->
    <nav class="absolute bottom-0 inset-x-0 h-20 glass-nav border-t border-slate-200/60 flex justify-around items-start pt-2 px-2 z-30">
      <button onclick="switchTab('dashboard')" id="nav-dashboard" class="flex flex-col items-center justify-center w-14 py-1 text-apple-blue transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5 mb-1"></i>
        <span class="text-[10px] font-medium">Beranda</span>
      </button>
      <button onclick="switchTab('planner')" id="nav-planner" class="flex flex-col items-center justify-center w-14 py-1 text-apple-gray transition-colors">
        <i data-lucide="calendar-days" class="w-5 h-5 mb-1"></i>
        <span class="text-[10px] font-medium">Planner</span>
      </button>
      <button onclick="switchTab('notes')" id="nav-notes" class="flex flex-col items-center justify-center w-14 py-1 text-apple-gray transition-colors">
        <i data-lucide="file-text" class="w-5 h-5 mb-1"></i>
        <span class="text-[10px] font-medium">Catatan</span>
      </button>
      <button onclick="switchTab('money')" id="nav-money" class="flex flex-col items-center justify-center w-14 py-1 text-apple-gray transition-colors">
        <i data-lucide="wallet" class="w-5 h-5 mb-1"></i>
        <span class="text-[10px] font-medium">Keuangan</span>
      </button>
      <button onclick="switchTab('progress')" id="nav-progress" class="flex flex-col items-center justify-center w-14 py-1 text-apple-gray transition-colors">
        <i data-lucide="trending-up" class="w-5 h-5 mb-1"></i>
        <span class="text-[10px] font-medium">Progres</span>
      </button>
    </nav>

    <!-- Home Indicator Line -->
    <div class="absolute bottom-1 inset-x-0 flex justify-center z-40 pointer-events-none">
      <div class="w-32 h-1 bg-slate-400/50 rounded-full"></div>
    </div>

  </div>

  <!-- ================= REUSABLE MODALS ================= -->

  <!-- 1. Universal Quick Action Modal -->
  <div id="modal-quick-add" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-slate-900">Tambah Cepat</h3>
        <button onclick="closeModal('modal-quick-add')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <button onclick="closeModal('modal-quick-add'); openTaskModal();" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 press-effect border border-slate-100">
          <div class="w-12 h-12 rounded-full bg-blue-100 text-apple-blue flex items-center justify-center mb-2">
            <i data-lucide="calendar" class="w-6 h-6"></i>
          </div>
          <span class="text-xs font-semibold text-slate-800">Jadwal / Tugas</span>
        </button>
        <button onclick="closeModal('modal-quick-add'); openNoteModal();" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 press-effect border border-slate-100">
          <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-2">
            <i data-lucide="file-plus" class="w-6 h-6"></i>
          </div>
          <span class="text-xs font-semibold text-slate-800">Catatan Baru</span>
        </button>
        <button onclick="closeModal('modal-quick-add'); openTransactionModal('expense');" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 press-effect border border-slate-100">
          <div class="w-12 h-12 rounded-full bg-red-100 text-apple-red flex items-center justify-center mb-2">
            <i data-lucide="arrow-down-left" class="w-6 h-6"></i>
          </div>
          <span class="text-xs font-semibold text-slate-800">Pengeluaran</span>
        </button>
        <button onclick="closeModal('modal-quick-add'); openTransactionModal('income');" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 press-effect border border-slate-100">
          <div class="w-12 h-12 rounded-full bg-emerald-100 text-apple-green flex items-center justify-center mb-2">
            <i data-lucide="arrow-up-right" class="w-6 h-6"></i>
          </div>
          <span class="text-xs font-semibold text-slate-800">Pemasukan</span>
        </button>
      </div>
    </div>
  </div>

  <!-- 2. Add / Edit Task Modal -->
  <div id="modal-add-task" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900" id="task-modal-title">Tambah Jadwal / Tugas</h3>
        <button onclick="closeModal('modal-add-task')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-task" onsubmit="handleSaveTask(event)" class="space-y-3">
        <input type="hidden" id="task-id" value="" />
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Judul Kegiatan</label>
          <input type="text" id="task-title" required placeholder="Contoh: Kuliah Pemrograman Web" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Kategori</label>
            <select id="task-category" class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue">
              <option value="Kuliah">Kuliah</option>
              <option value="Rapat">Rapat</option>
              <option value="Organisasi">Organisasi</option>
              <option value="Pribadi">Pribadi</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Prioritas</label>
            <select id="task-priority" class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue">
              <option value="Tinggi">Tinggi 🔥</option>
              <option value="Sedang" selected>Sedang ⚡</option>
              <option value="Rendah">Rendah ☕</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal</label>
            <input type="date" id="task-date" required class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Waktu</label>
            <input type="time" id="task-time" required class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
          </div>
        </div>
        <button type="submit" id="btn-submit-task" class="w-full py-3 bg-apple-blue text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2">Simpan Aktivitas</button>
      </form>
    </div>
  </div>

  <!-- 3. Add / Edit Note Modal (With Multi-Image Upload & Preview Grid with Cross 'x' Buttons!) -->
  <div id="modal-add-note" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900" id="note-modal-title">Catatan Baru</h3>
        <button onclick="closeModal('modal-add-note')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-note" onsubmit="handleSaveNote(event)" enctype="multipart/form-data" class="space-y-3">
        <input type="hidden" id="note-id" value="" />
        <input type="hidden" id="note-target-folder-id" value="" />

        <div id="folder-assignment-container">
          <!-- Populated via JS -->
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Judul Catatan</label>
          <input type="text" id="note-title" required placeholder="Judul ringkasan atau materi..." class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Isi Catatan</label>
          <textarea id="note-content" rows="4" required placeholder="Tuliskan materi, poin penting, atau rangkuman..." class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue resize-none"></textarea>
        </div>
        
        <div>
          <div class="flex justify-between items-center mb-1">
            <label class="block text-xs font-semibold text-slate-500">Lampirkan Foto / Gambar (Multi-Gambar Support)</label>
            <span class="text-[10px] text-slate-400 italic">Bisa upload lebih dari 1 foto</span>
          </div>
          <input type="file" id="note-image-file" multiple accept="image/*" onchange="previewSelectedImages(event)" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" />
          
          <div id="image-preview-grid" class="mt-3 grid grid-cols-3 gap-2 hidden">
            <!-- Rendered dynamically in JS -->
          </div>
        </div>

        <div class="flex items-center space-x-2 pt-1">
          <input type="checkbox" id="note-pin" class="w-4 h-4 rounded text-apple-blue focus:ring-apple-blue" />
          <label for="note-pin" class="text-xs font-medium text-slate-700">Sematkan Catatan (Pin)</label>
        </div>
        <button type="submit" id="btn-save-note" class="w-full py-3 bg-amber-500 text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2 flex justify-center items-center gap-2">
          <span>Simpan Catatan</span>
        </button>
      </form>
    </div>
  </div>

  <!-- 4. Add Transaction Modal -->
  <div id="modal-add-transaction" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900" id="tx-modal-title">Tambah Transaksi</h3>
        <button onclick="closeModal('modal-add-transaction')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-transaction" onsubmit="handleSaveTransaction(event)" enctype="multipart/form-data" class="space-y-3">
        <input type="hidden" id="tx-type" value="expense" />
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Jumlah (Rp)</label>
          <input type="number" id="tx-amount" required placeholder="25000" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-lg font-bold focus:outline-none focus:ring-2 focus:ring-apple-blue" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Keterangan</label>
          <input type="text" id="tx-title" required placeholder="Makan Siang Kantin / Kiriman Orang Tua" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Kategori</label>
          <select id="tx-category" class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none">
            <option value="Makanan">Makanan & Minuman</option>
            <option value="Akademik">Buku & Akademik</option>
            <option value="Transport">Transportasi</option>
            <option value="Hiburan">Hiburan & Nongkrong</option>
            <option value="Pemasukan">Pemasukan / Uang Saku</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Lampirkan Bukti / Foto Nota (Opsional)</label>
          <input type="file" id="tx-image-file" accept="image/*" onchange="previewTransactionImage(event)" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" />
          
          <div id="tx-image-preview-container" class="mt-2 relative hidden">
            <img id="tx-image-preview-element" src="" class="h-28 w-full object-cover rounded-xl border border-slate-200" />
            <button type="button" onclick="removeTransactionImage()" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-apple-red text-white flex items-center justify-center shadow-md press-effect" title="Hapus Foto Nota">
              <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
          </div>
        </div>

        <button type="submit" id="tx-submit-btn" class="w-full py-3 bg-apple-red text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2">Catat Transaksi</button>
      </form>
    </div>
  </div>

  <!-- 5. Add Folder Modal -->
  <div id="modal-add-folder" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900">Folder Baru</h3>
        <button onclick="closeModal('modal-add-folder')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-folder" onsubmit="handleSaveFolder(event)" class="space-y-3">
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Folder</label>
          <input type="text" id="folder-name" required placeholder="Contoh: Semester 6, HMIF, Riset..." class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-apple-blue" />
        </div>
        <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2">Buat Folder</button>
      </form>
    </div>
  </div>

  <!-- 6. Edit Savings / Deposit Savings Modal -->
  <div id="modal-edit-savings" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900">Kelola Target Tabungan</h3>
        <button onclick="closeModal('modal-edit-savings')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-savings" onsubmit="handleSaveSavings(event)" class="space-y-3">
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Target Tabungan</label>
          <input type="text" id="savings-name" required placeholder="Contoh: Beli Laptop Pro" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none" />
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Target Total (Rp)</label>
            <input type="number" id="savings-target" required class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Terkumpul Saat Ini (Rp)</label>
            <input type="number" id="savings-current" required class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none" />
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Tambah Setoran Baru (Rp - Opsional)</label>
          <input type="number" id="savings-deposit" placeholder="Misal: 50000" class="w-full px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-800 text-sm font-semibold focus:outline-none" />
        </div>
        <button type="submit" class="w-full py-3 bg-apple-blue text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2">Simpan Perubahan Tabungan</button>
      </form>
    </div>
  </div>

  <!-- 7. Add Habit Modal -->
  <div id="modal-add-habit" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white w-full max-w-[420px] rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900">Kebiasaan Baru</h3>
        <button onclick="closeModal('modal-add-habit')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>
      <form id="form-habit" onsubmit="handleSaveHabit(event)" class="space-y-3">
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Kebiasaan</label>
          <input type="text" id="habit-title" required placeholder="Contoh: Baca 1 Bab Buku, Olahraga 15 Menit..." class="w-full px-4 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none" />
        </div>
        <button type="submit" class="w-full py-3 bg-apple-green text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2">Simpan Kebiasaan</button>
      </form>
    </div>
  </div>

  <!-- 8. CONFIRMATION DELETION MODAL (Apple Style Alert Dialog) -->
  <div id="modal-confirm-delete" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200 p-4">
    <div class="bg-white w-full max-w-[340px] rounded-3xl p-6 shadow-2xl text-center space-y-4 transform scale-95 transition-transform duration-200">
      <div class="w-14 h-14 rounded-full bg-red-100 text-apple-red flex items-center justify-center mx-auto">
        <i data-lucide="alert-triangle" class="w-7 h-7"></i>
      </div>
      <div>
        <h3 id="confirm-delete-title" class="text-base font-bold text-slate-900">Konfirmasi Hapus</h3>
        <p id="confirm-delete-message" class="text-xs text-slate-500 mt-1 leading-relaxed">Apakah Anda yakin ingin menghapus item ini? Data dan berkas gambar yang terhapus tidak dapat dikembalikan.</p>
      </div>
      <div class="grid grid-cols-2 gap-3 pt-2">
        <button onclick="closeModal('modal-confirm-delete')" class="py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold text-xs press-effect">Batal</button>
        <button id="confirm-delete-btn" onclick="executePendingDelete()" class="py-2.5 bg-apple-red text-white rounded-xl font-semibold text-xs shadow-md press-effect">Hapus</button>
      </div>
    </div>
  </div>

  <!-- 9. FULLSCREEN IMAGE VIEWER MODAL -->
  <div id="modal-image-viewer" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200 p-4" onclick="closeModal('modal-image-viewer')">
    <div class="relative max-w-full max-h-full flex flex-col items-center justify-center">
      <button onclick="closeModal('modal-image-viewer')" class="absolute -top-12 right-0 text-white hover:text-slate-300 p-2">
        <i data-lucide="x" class="w-8 h-8"></i>
      </button>
      <img id="full-image-element" src="" class="max-h-[85vh] max-w-[90vw] object-contain rounded-2xl shadow-2xl" />
    </div>
  </div>

  <!-- JavaScript Core Engine -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('./sw.js').catch(err => console.log('SW registration failed', err));
      });
    }

    let appState = {
      activeTab: 'dashboard',
      tasks: [],
      notes: [],
      folders: [],
      transactions: [],
      habits: [],
      currentFolderId: null,
      selectedPlannerDate: null,
      plannerMonthOffset: 0, // Month navigation offset
      plannerCategoryFilter: 'Semua',
      settings: {
        dailyBudget: 100000,
        savingsTarget: 5000000,
        savingsCurrent: 2150000,
        savingsName: 'Beli Laptop Pro'
      }
    };

    let pendingDeleteAction = null;

    let activeRetainedImages = [];
    let activeNewImageFiles = [];
    let activeRemovedImages = [];

    let noteCarouselIntervals = {};

    async function fetchAppState() {
      const syncIcon = document.getElementById('sync-icon');
      const syncText = document.getElementById('sync-text');
      if (syncIcon) syncIcon.classList.add('animate-spin');

      try {
        const response = await fetch('./api/state.php');
        const result = await response.json();
        if (result.success) {
          appState.tasks = result.data.tasks || [];
          appState.folders = result.data.folders || [];
          appState.notes = result.data.notes || [];
          appState.transactions = result.data.transactions || [];
          appState.habits = result.data.habits || [];
          if (result.data.settings) {
            appState.settings = result.data.settings;
          }
          if (syncText) syncText.textContent = 'Synced';
          switchTab(appState.activeTab);
        } else {
          if (syncText) syncText.textContent = 'Err Sync';
        }
      } catch (err) {
        if (syncText) syncText.textContent = 'Offline';
      } finally {
        if (syncIcon) syncIcon.classList.remove('animate-spin');
      }
    }

    setInterval(fetchAppState, 15000);

    function formatRupiah(amount) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
    }

    function getTodayString() {
      return new Date().toISOString().split('T')[0];
    }

    function getFormattedDate() {
      const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
      return new Date().toLocaleDateString('id-ID', options);
    }

    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      document.getElementById('status-bar-time').textContent = `${hours}:${minutes}`;
    }
    setInterval(updateClock, 1000);

    function clearAllCarouselTimers() {
      Object.keys(noteCarouselIntervals).forEach(id => {
        clearInterval(noteCarouselIntervals[id]);
      });
      noteCarouselIntervals = {};
    }

    function switchTab(tabName) {
      clearAllCarouselTimers();
      appState.activeTab = tabName;

      const tabs = ['dashboard', 'planner', 'notes', 'money', 'progress'];
      tabs.forEach(t => {
        const btn = document.getElementById(`nav-${t}`);
        if (btn) {
          if (t === tabName) {
            btn.className = 'flex flex-col items-center justify-center w-14 py-1 text-apple-blue transition-colors font-bold scale-105';
          } else {
            btn.className = 'flex flex-col items-center justify-center w-14 py-1 text-apple-gray transition-colors opacity-70';
          }
        }
      });

      const container = document.getElementById('app-content');
      const titleEl = document.getElementById('header-title');

      switch (tabName) {
        case 'dashboard':
          titleEl.textContent = 'Dashboard';
          renderDashboard(container);
          break;
        case 'planner':
          titleEl.textContent = 'Planner & Jadwal';
          renderPlanner(container);
          break;
        case 'notes':
          titleEl.textContent = 'Catatan Mahasiswa';
          renderNotes(container);
          break;
        case 'money':
          titleEl.textContent = 'Keuangan';
          renderMoney(container);
          break;
        case 'progress':
          titleEl.textContent = 'Progres & Statistik';
          renderProgress(container);
          break;
      }

      if (window.lucide) lucide.createIcons();
    }

    // --- 1. DASHBOARD VIEW ---
    function renderDashboard(container) {
      const todayStr = getTodayString();
      const todayExpenses = appState.transactions
        .filter(t => t.date === todayStr && t.type === 'expense')
        .reduce((sum, t) => sum + Number(t.amount), 0);

      const budgetLimit = appState.settings.dailyBudget;
      const budgetPercent = Math.min(Math.round((todayExpenses / budgetLimit) * 100), 100);

      let alertBannerHTML = '';
      if (todayExpenses > budgetLimit) {
        alertBannerHTML = `
          <div class="mb-4 p-3.5 rounded-2xl bg-apple-red text-white shadow-md flex items-start space-x-3 animate-pulse">
            <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <div>
              <p class="text-xs font-bold uppercase tracking-wider">Over Budget!</p>
              <p class="text-xs opacity-90">Pengeluaran hari ini telah melebihi batas harian ${formatRupiah(budgetLimit)}.</p>
            </div>
          </div>
        `;
      } else if (budgetPercent >= 85) {
        alertBannerHTML = `
          <div class="mb-4 p-3.5 rounded-2xl bg-amber-500 text-slate-900 shadow-md flex items-start space-x-3">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <div>
              <p class="text-xs font-bold uppercase tracking-wider">Budget Hampir Habis (${budgetPercent}%)</p>
              <p class="text-xs opacity-90">Anda telah menggunakan sebagian besar anggaran harian.</p>
            </div>
          </div>
        `;
      }

      const upcomingTasks = appState.tasks.filter(t => !t.completed).slice(0, 3);
      const nextSchedule = upcomingTasks[0];

      const latestNotes = [...appState.notes]
        .sort((a, b) => new Date(b.updatedAt || 0) - new Date(a.updatedAt || 0))
        .slice(0, 2);

      container.innerHTML = `
        <div class="space-y-4">
          <div class="p-4 rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-lg">
            <p class="text-xs font-medium opacity-80">${getFormattedDate()}</p>
            <h2 class="text-xl font-bold mt-1">Halo, Mahasiswa! 👋</h2>
            <p class="text-xs opacity-90 mt-1">Data Anda tersinkronisasi otomatis di Xiaomi 13T & Laptop.</p>
          </div>

          ${alertBannerHTML}

          <!-- Next Schedule Card -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-[10px] font-bold uppercase tracking-wider text-apple-blue bg-blue-50 px-2.5 py-1 rounded-full">Agenda Berikutnya</span>
              <span class="text-xs text-slate-400 font-medium">${nextSchedule ? nextSchedule.time : '-'}</span>
            </div>
            ${nextSchedule ? `
              <h3 class="text-base font-bold text-slate-800">${nextSchedule.title}</h3>
              <div class="flex items-center space-x-2 text-xs text-slate-500">
                <span class="px-2 py-0.5 bg-slate-100 rounded-md font-medium text-slate-600">${nextSchedule.category}</span>
                <span>• Prioritas: ${nextSchedule.priority}</span>
              </div>
            ` : `
              <p class="text-xs text-slate-400 italic py-2">Tidak ada agenda mendesak hari ini. Bebas!</p>
            `}
          </div>

          <!-- Catatan Terbaru Widget -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                  <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Catatan Terbaru & Quick Ideas</h3>
              </div>
              <button onclick="switchTab('notes')" class="text-xs font-semibold text-apple-blue press-effect">Lihat Semua</button>
            </div>
            <div class="space-y-2">
              ${latestNotes.length > 0 ? latestNotes.map(note => {
                const folder = appState.folders.find(f => Number(f.id) === Number(note.folderId));
                const img = note.imageUrl || (note.imageUrls && note.imageUrls[0]);
                return `
                  <div onclick="switchTab('notes')" class="p-3 bg-amber-50/50 rounded-2xl border border-amber-100 flex items-start justify-between press-effect cursor-pointer space-x-3">
                    <div class="space-y-1 flex-1">
                      <div class="flex items-center space-x-2">
                        <span class="text-xs font-bold text-slate-900 line-clamp-1">${note.title}</span>
                        ${folder ? `<span class="text-[9px] px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-semibold">${folder.name}</span>` : ''}
                      </div>
                      <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">${note.content}</p>
                    </div>
                    ${img ? `
                      <img src="${img}" class="w-12 h-12 object-cover rounded-xl border border-amber-200 flex-shrink-0" />
                    ` : ''}
                  </div>
                `;
              }).join('') : `
                <div class="p-3 bg-slate-50 rounded-2xl text-center">
                  <p class="text-xs text-slate-400 italic">Belum ada catatan. Catat ide Anda dengan cepat!</p>
                </div>
              `}
            </div>
          </div>

          <!-- Budget Summary Card -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-xs text-slate-400 font-medium">Pengeluaran Hari Ini</p>
                <h3 class="text-lg font-bold text-slate-900">${formatRupiah(todayExpenses)} <span class="text-xs font-normal text-slate-400">/ ${formatRupiah(budgetLimit)}</span></h3>
              </div>
              <button onclick="switchTab('money')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 press-effect">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
              </button>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
              <div class="h-full ${todayExpenses > budgetLimit ? 'bg-apple-red' : budgetPercent >= 85 ? 'bg-amber-500' : 'bg-apple-blue'} transition-all duration-500" style="width: ${budgetPercent}%"></div>
            </div>
          </div>

          <!-- Quick Actions Grid -->
          <div class="grid grid-cols-3 gap-2">
            <button onclick="switchTab('planner')" class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center space-y-1.5 press-effect">
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-apple-blue flex items-center justify-center">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </div>
              <span class="text-[11px] font-semibold text-slate-700">Planner</span>
            </button>
            <button onclick="switchTab('notes')" class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center space-y-1.5 press-effect">
              <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5"></i>
              </div>
              <span class="text-[11px] font-semibold text-slate-700">Catatan</span>
            </button>
            <button onclick="switchTab('money')" class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center space-y-1.5 press-effect">
              <div class="w-9 h-9 rounded-xl bg-emerald-50 text-apple-green flex items-center justify-center">
                <i data-lucide="wallet" class="w-5 h-5"></i>
              </div>
              <span class="text-[11px] font-semibold text-slate-700">Keuangan</span>
            </button>
          </div>

          <!-- Today's Tasks Preview -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <h3 class="text-sm font-bold text-slate-800">Tugas & Agenda Mendatang</h3>
              <button onclick="switchTab('planner')" class="text-xs font-semibold text-apple-blue">Lihat Semua</button>
            </div>
            <div class="space-y-2">
              ${upcomingTasks.length > 0 ? upcomingTasks.map(task => `
                <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl">
                  <div class="flex items-center space-x-3">
                    <button onclick="toggleTaskStatus(${task.id}, ${task.completed})" class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center press-effect">
                      ${task.completed ? '<div class="w-3 h-3 bg-apple-blue rounded-full"></div>' : ''}
                    </button>
                    <div>
                      <p class="text-xs font-semibold text-slate-800 ${task.completed ? 'line-through text-slate-400' : ''}">${task.title}</p>
                      <p class="text-[10px] text-slate-400">${task.category} • ${task.time}</p>
                    </div>
                  </div>
                  <span class="text-[10px] px-2 py-0.5 rounded-full ${task.priority === 'Tinggi' ? 'bg-red-100 text-apple-red font-semibold' : 'bg-slate-200 text-slate-600'}">${task.priority}</span>
                </div>
              `).join('') : '<p class="text-xs text-slate-400 italic text-center py-2">Semua tugas selesai! 🎉</p>'}
            </div>
          </div>

          <!-- Habits Quick Tracker -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <h3 class="text-sm font-bold text-slate-800">Kebiasaan Hari Ini</h3>
              <button onclick="openHabitModal()" class="text-xs text-apple-blue font-semibold">+ Kebiasaan</button>
            </div>
            <div class="space-y-2">
              ${appState.habits.map(habit => `
                <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl">
                  <div class="flex items-center space-x-2.5">
                    <button onclick="toggleHabit(${habit.id})" class="w-6 h-6 rounded-lg ${habit.completedToday ? 'bg-apple-green text-white' : 'bg-slate-200 text-slate-400'} flex items-center justify-center press-effect">
                      <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                    <span class="text-xs font-semibold text-slate-800">${habit.title}</span>
                  </div>
                  <span class="text-[10px] text-slate-400 font-medium">🔥 ${habit.streak} hari</span>
                </div>
              `).join('')}
            </div>
          </div>

        </div>
      `;
    }

    // --- 2. PLANNER VIEW (WITH FULL 1-MONTH CALENDAR GRID & RED TASK INDICATORS!) ---
    function renderPlanner(container) {
      const now = new Date();
      // Calculate target year & month from month offset
      const targetDate = new Date(now.getFullYear(), now.getMonth() + appState.plannerMonthOffset, 1);
      const year = targetDate.getFullYear();
      const month = targetDate.getMonth();

      const monthYearLabel = targetDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

      // First day of month (0 = Sun, 1 = Mon, ..., 6 = Sat)
      const firstDayIndex = new Date(year, month, 1).getDay();
      // Convert to Monday-first (0 = Mon, 1 = Tue, ..., 6 = Sun)
      const mondayFirstIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

      // Total days in target month
      const totalDays = new Date(year, month + 1, 0).getDate();

      // Total days in previous month (for padding)
      const prevMonthTotalDays = new Date(year, month, 0).getDate();

      const calendarGridCells = [];

      // 1. Padding days from previous month
      for (let i = mondayFirstIndex - 1; i >= 0; i--) {
        const dayNum = prevMonthTotalDays - i;
        const prevMonthDate = new Date(year, month - 1, dayNum);
        const isoDate = prevMonthDate.toISOString().split('T')[0];
        calendarGridCells.push({
          dayNumber: dayNum,
          isoDate,
          isCurrentMonth: false,
          isToday: isoDate === getTodayString(),
          isSelected: isoDate === appState.selectedPlannerDate
        });
      }

      // 2. Days of current target month
      for (let d = 1; d <= totalDays; d++) {
        const currentMonthDate = new Date(year, month, d);
        // Format ISO date string YYYY-MM-DD
        const yearStr = currentMonthDate.getFullYear();
        const monthStr = String(currentMonthDate.getMonth() + 1).padStart(2, '0');
        const dayStr = String(d).padStart(2, '0');
        const isoDate = `${yearStr}-${monthStr}-${dayStr}`;

        calendarGridCells.push({
          dayNumber: d,
          isoDate,
          isCurrentMonth: true,
          isToday: isoDate === getTodayString(),
          isSelected: isoDate === appState.selectedPlannerDate
        });
      }

      // 3. Padding days for next month to complete the 7-column grid
      const remainingCells = (7 - (calendarGridCells.length % 7)) % 7;
      for (let d = 1; d <= remainingCells; d++) {
        const nextMonthDate = new Date(year, month + 1, d);
        const isoDate = nextMonthDate.toISOString().split('T')[0];
        calendarGridCells.push({
          dayNumber: d,
          isoDate,
          isCurrentMonth: false,
          isToday: isoDate === getTodayString(),
          isSelected: isoDate === appState.selectedPlannerDate
        });
      }

      // Filter tasks by category & selected date
      let filteredTasks = appState.tasks;

      if (appState.plannerCategoryFilter !== 'Semua') {
        filteredTasks = filteredTasks.filter(t => t.category === appState.plannerCategoryFilter);
      }

      if (appState.selectedPlannerDate) {
        filteredTasks = filteredTasks.filter(t => t.date === appState.selectedPlannerDate);
      }

      const categories = ['Semua', 'Kuliah', 'Rapat', 'Organisasi', 'Pribadi'];

      container.innerHTML = `
        <div class="space-y-4">
          <!-- FULL 1-MONTH CALENDAR GRID -->
          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <!-- Month Header Navigation -->
            <div class="flex justify-between items-center mb-1">
              <span class="text-sm font-bold text-slate-900 capitalize">${monthYearLabel}</span>
              <div class="flex items-center space-x-1">
                <button onclick="changePlannerMonth(-1)" class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center press-effect text-slate-700 hover:bg-slate-200" title="Bulan Sebelumnya">
                  <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button onclick="resetPlannerMonth()" class="px-2.5 py-1 rounded-full bg-blue-50 text-apple-blue font-bold text-[10px] press-effect">
                  Bulan Ini
                </button>
                <button onclick="changePlannerMonth(1)" class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center press-effect text-slate-700 hover:bg-slate-200" title="Bulan Berikutnya">
                  <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
              </div>
            </div>

            <!-- Day Name Headers (Monday to Sunday) -->
            <div class="grid grid-cols-7 gap-1 text-center font-bold text-[10px] text-slate-400 py-1 border-b border-slate-100">
              <span>Sen</span>
              <span>Sel</span>
              <span>Rab</span>
              <span>Kam</span>
              <span>Jum</span>
              <span>Sab</span>
              <span>Min</span>
            </div>

            <!-- Full Month Grid Cells (7 Columns x 5-6 Rows) -->
            <div class="grid grid-cols-7 gap-1 text-center">
              ${calendarGridCells.map(cell => {
                // Find tasks scheduled on this date
                const dayTasks = appState.tasks.filter(t => t.date === cell.isoDate);
                const incompleteTasks = dayTasks.filter(t => !t.completed);
                const hasHighPriority = incompleteTasks.some(t => t.priority === 'Tinggi');

                return `
                  <button onclick="selectPlannerDate('${cell.isoDate}')" class="relative flex flex-col items-center justify-center h-10 rounded-2xl transition-all press-effect ${
                    cell.isSelected ? 'bg-slate-900 text-white font-bold shadow-md scale-105' :
                    cell.isToday ? 'bg-apple-blue text-white font-bold' :
                    cell.isCurrentMonth ? 'bg-slate-50 text-slate-700 hover:bg-slate-100' : 'bg-slate-50/40 text-slate-300'
                  }">
                    <span class="text-xs font-semibold">${cell.dayNumber}</span>
                    
                    <!-- Visual Red/Blue Task Indicator Dots -->
                    ${dayTasks.length > 0 ? `
                      <div class="absolute bottom-1 flex items-center space-x-0.5">
                        ${incompleteTasks.length > 0 ? `
                          <span class="w-1.5 h-1.5 rounded-full ${hasHighPriority ? 'bg-apple-red animate-pulse' : 'bg-amber-400'}"></span>
                        ` : `
                          <span class="w-1.5 h-1.5 rounded-full bg-apple-green"></span>
                        `}
                      </div>
                    ` : ''}
                  </button>
                `;
              }).join('')}
            </div>

            <!-- Legend Info for Task Indicators -->
            <div class="flex items-center justify-center space-x-4 text-[10px] text-slate-400 pt-2 border-t border-slate-50">
              <div class="flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-apple-red"></span>
                <span>Tugas Prioritas / Ada Tugas</span>
              </div>
              <div class="flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-apple-green"></span>
                <span>Tugas Selesai</span>
              </div>
            </div>
          </div>

          <!-- Date Filter Indicator Banner -->
          ${appState.selectedPlannerDate ? `
            <div class="flex justify-between items-center p-2.5 bg-blue-50 rounded-2xl text-xs text-apple-blue px-4">
              <span class="font-medium">Filter Tanggal: <b>${appState.selectedPlannerDate}</b></span>
              <button onclick="selectPlannerDate(null)" class="font-bold underline text-[11px] press-effect">Tampilkan Semua</button>
            </div>
          ` : ''}

          <!-- Category Filter Pills -->
          <div class="flex space-x-1.5 overflow-x-auto hide-scrollbar pb-1">
            ${categories.map(cat => `
              <button onclick="selectPlannerCategory('${cat}')" class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all press-effect ${
                appState.plannerCategoryFilter === cat ? 'bg-apple-blue text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200/60'
              }">${cat}</button>
            `).join('')}
          </div>

          <!-- Add Task Action Button -->
          <button onclick="openTaskModal()" class="w-full py-3 bg-apple-blue text-white rounded-2xl font-semibold text-xs shadow-md flex items-center justify-center space-x-2 press-effect">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Jadwal / Tugas Baru</span>
          </button>

          <!-- Tasks List -->
          <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Agenda (${filteredTasks.length})</h3>
            </div>

            ${filteredTasks.length > 0 ? filteredTasks.map(task => `
              <div class="p-3.5 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <button onclick="toggleTaskStatus(${task.id}, ${task.completed})" class="w-6 h-6 rounded-full border-2 ${task.completed ? 'bg-apple-green border-apple-green text-white' : 'border-slate-300'} flex items-center justify-center press-effect">
                    ${task.completed ? '<i data-lucide="check" class="w-3.5 h-3.5"></i>' : ''}
                  </button>
                  <div>
                    <h4 class="text-xs font-bold text-slate-800 ${task.completed ? 'line-through text-slate-400' : ''}">${task.title}</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">${task.category} • ${task.date} @ ${task.time}</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="text-[10px] px-2 py-0.5 rounded-full font-medium ${
                    task.priority === 'Tinggi' ? 'bg-red-50 text-apple-red font-bold' : 'bg-slate-100 text-slate-600'
                  }">${task.priority}</span>
                  <button onclick="editTask(${task.id})" class="text-slate-400 hover:text-apple-blue press-effect p-1">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                  </button>
                  <button onclick="requestDeleteTask(${task.id}, '${task.title.replace(/'/g, "\\'")}')" class="text-slate-300 hover:text-apple-red press-effect p-1">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </div>
            `).join('') : '<p class="text-xs text-slate-400 italic text-center py-6">Tidak ada agenda ditemukan.</p>'}
          </div>
        </div>
      `;
    }

    function changePlannerMonth(offset) {
      appState.plannerMonthOffset += offset;
      switchTab('planner');
    }

    function resetPlannerMonth() {
      appState.plannerMonthOffset = 0;
      appState.selectedPlannerDate = getTodayString();
      switchTab('planner');
    }

    function selectPlannerDate(isoDate) {
      if (appState.selectedPlannerDate === isoDate) {
        appState.selectedPlannerDate = null;
      } else {
        appState.selectedPlannerDate = isoDate;
      }
      switchTab('planner');
    }

    function selectPlannerCategory(cat) {
      appState.plannerCategoryFilter = cat;
      switchTab('planner');
    }

    // --- 3. NOTES VIEW ---
    function renderNotes(container) {
      const currentFolder = appState.folders.find(f => Number(f.id) === Number(appState.currentFolderId));
      
      let currentNotes = appState.notes.filter(n => {
        if (!appState.currentFolderId) return true;
        return Number(n.folderId) === Number(appState.currentFolderId);
      });

      currentNotes.sort((a, b) => (b.isPinned ? 1 : 0) - (a.isPinned ? 1 : 0));

      container.innerHTML = `
        <div class="space-y-4">
          <div class="p-3.5 rounded-2xl bg-white shadow-sm border border-slate-100 flex justify-between items-center">
            <div class="flex items-center space-x-2">
              ${appState.currentFolderId ? `
                <button onclick="navigateToFolder(null)" class="text-apple-blue press-effect flex items-center space-x-1">
                  <i data-lucide="arrow-left" class="w-5 h-5"></i>
                  <span class="text-xs font-semibold">Kembali</span>
                </button>
                <span class="text-xs text-slate-300">/</span>
                <span class="text-xs font-bold text-slate-800">${currentFolder ? currentFolder.name : 'Folder'}</span>
              ` : `
                <i data-lucide="folder" class="w-5 h-5 text-amber-500"></i>
                <span class="text-xs font-bold text-slate-800">Semua Catatan</span>
              `}
            </div>
            <button onclick="openFolderModal()" class="text-xs text-apple-blue font-semibold flex items-center space-x-1 press-effect">
              <i data-lucide="folder-plus" class="w-4 h-4"></i>
              <span>Folder Baru</span>
            </button>
          </div>

          ${!appState.currentFolderId ? `
            <div class="space-y-2">
              <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">Daftar Folder</span>
              <div class="grid grid-cols-2 gap-2.5">
                ${appState.folders.map(folder => {
                  const count = appState.notes.filter(n => Number(n.folderId) === Number(folder.id)).length;
                  return `
                    <div onclick="navigateToFolder(${folder.id})" class="p-3.5 rounded-2xl bg-white shadow-sm border border-slate-100 flex flex-col justify-between h-24 press-effect cursor-pointer">
                      <div class="flex justify-between items-start">
                        <i data-lucide="folder" class="w-7 h-7 text-amber-400 fill-amber-100"></i>
                        <button onclick="event.stopPropagation(); requestDeleteFolder(${folder.id}, '${folder.name.replace(/'/g, "\\'")}')" class="text-slate-300 hover:text-apple-red">
                          <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                      </div>
                      <div>
                        <h4 class="text-xs font-bold text-slate-800 leading-tight">${folder.name}</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">${count} Catatan</p>
                      </div>
                    </div>
                  `;
                }).join('')}
              </div>
            </div>
          ` : ''}

          <button onclick="openNoteModal()" class="w-full py-3 bg-amber-500 text-white rounded-2xl font-semibold text-xs shadow-md flex items-center justify-center space-x-2 press-effect">
            <i data-lucide="file-plus" class="w-4 h-4"></i>
            <span>Tulis Catatan Baru</span>
          </button>

          <div class="space-y-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">Catatan Tersimpan (${currentNotes.length})</span>
            
            ${currentNotes.length > 0 ? currentNotes.map(note => {
              const images = note.imageUrls && note.imageUrls.length > 0 ? note.imageUrls : (note.imageUrl ? [note.imageUrl] : []);
              return `
                <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 space-y-2 relative">
                  <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-1.5">
                      ${note.isPinned ? '<i data-lucide="pin" class="w-4 h-4 text-amber-500 fill-amber-500"></i>' : ''}
                      <h4 class="text-xs font-bold text-slate-900">${note.title}</h4>
                    </div>
                    <div class="flex items-center space-x-2">
                      <button onclick="editNote(${note.id})" class="text-slate-400 hover:text-apple-blue press-effect p-1" title="Edit Catatan">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                      </button>
                      <button onclick="requestDeleteNote(${note.id}, '${note.title.replace(/'/g, "\\'")}')" class="text-slate-300 hover:text-apple-red press-effect p-1" title="Hapus Catatan">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                      </button>
                    </div>
                  </div>
                  
                  <p class="text-xs text-slate-600 line-clamp-3 whitespace-pre-line leading-relaxed">${note.content}</p>
                  
                  ${images.length > 0 ? `
                    <div class="pt-1 relative rounded-xl overflow-hidden group">
                      <div id="note-carousel-img-container-${note.id}" class="h-40 w-full bg-slate-900">
                        <img id="note-carousel-img-${note.id}" data-current-index="0" data-images='${JSON.stringify(images)}' src="${images[0]}" onclick="viewFullImage(this.src)" class="h-full w-full object-cover cursor-pointer transition-all duration-500" />
                      </div>
                      
                      ${images.length > 1 ? `
                        <div class="absolute bottom-2 inset-x-0 flex justify-center items-center space-x-1.5 z-10 pointer-events-none">
                          ${images.map((_, idx) => `
                            <span id="carousel-dot-${note.id}-${idx}" class="w-2 h-2 rounded-full ${idx === 0 ? 'bg-white scale-125' : 'bg-white/50'} transition-all duration-300"></span>
                          `).join('')}
                        </div>
                        <span class="absolute top-2 right-2 px-2 py-0.5 bg-black/60 text-white font-semibold text-[10px] rounded-full backdrop-blur-sm pointer-events-none">
                          <span id="carousel-num-${note.id}">1</span> / ${images.length}
                        </span>
                      ` : ''}
                    </div>
                  ` : ''}

                  <div class="pt-2 flex justify-between items-center text-[10px] text-slate-400 border-t border-slate-50">
                    <span>${note.updatedAt ? new Date(note.updatedAt).toLocaleDateString('id-ID') : ''}</span>
                    <button onclick="togglePinNote(${note.id})" class="text-amber-600 hover:text-amber-700 font-semibold px-2 py-1 bg-amber-50 rounded-lg press-effect flex items-center space-x-1">
                      <i data-lucide="pin" class="w-3 h-3"></i>
                      <span>${note.isPinned ? 'Lepas Pin' : 'Sematkan'}</span>
                    </button>
                  </div>
                </div>
              `;
            }).join('') : '<p class="text-xs text-slate-400 italic text-center py-6">Tidak ada catatan dalam folder ini.</p>'}
          </div>
        </div>
      `;

      currentNotes.forEach(note => {
        const images = note.imageUrls && note.imageUrls.length > 0 ? note.imageUrls : (note.imageUrl ? [note.imageUrl] : []);
        if (images.length > 1) {
          startNoteCarousel(note.id, images);
        }
      });
    }

    function startNoteCarousel(noteId, images) {
      if (noteCarouselIntervals[noteId]) clearInterval(noteCarouselIntervals[noteId]);
      
      let currentIndex = 0;
      noteCarouselIntervals[noteId] = setInterval(() => {
        currentIndex = (currentIndex + 1) % images.length;
        const imgEl = document.getElementById(`note-carousel-img-${noteId}`);
        const numEl = document.getElementById(`carousel-num-${noteId}`);
        if (imgEl) {
          imgEl.style.opacity = '0.4';
          setTimeout(() => {
            imgEl.src = images[currentIndex];
            imgEl.style.opacity = '1';
          }, 150);
        }
        if (numEl) {
          numEl.textContent = currentIndex + 1;
        }
        images.forEach((_, idx) => {
          const dot = document.getElementById(`carousel-dot-${noteId}-${idx}`);
          if (dot) {
            if (idx === currentIndex) {
              dot.className = 'w-2 h-2 rounded-full bg-white scale-125 transition-all duration-300';
            } else {
              dot.className = 'w-2 h-2 rounded-full bg-white/50 transition-all duration-300';
            }
          }
        });
      }, 3000);
    }

    // --- 4. MONEY VIEW ---
    function renderMoney(container) {
      const todayStr = getTodayString();
      const totalIncome = appState.transactions.filter(t => t.type === 'income').reduce((sum, t) => sum + Number(t.amount), 0);
      const totalExpense = appState.transactions.filter(t => t.type === 'expense').reduce((sum, t) => sum + Number(t.amount), 0);
      const balance = totalIncome - totalExpense;

      const savingsPercent = Math.min(Math.round((appState.settings.savingsCurrent / appState.settings.savingsTarget) * 100), 100);

      container.innerHTML = `
        <div class="space-y-4">
          <div class="p-5 rounded-3xl bg-slate-900 text-white shadow-xl space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-xs font-semibold text-slate-400">Total Saldo Dompet</span>
              <span class="text-[10px] px-2 py-0.5 bg-emerald-500/20 text-emerald-400 rounded-full font-bold">MySQL Live</span>
            </div>
            <div>
              <h2 class="text-2xl font-extrabold tracking-tight">${formatRupiah(balance)}</h2>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-800 text-xs">
              <div>
                <p class="text-slate-400 text-[10px]">Pemasukan</p>
                <p class="font-bold text-apple-green">+${formatRupiah(totalIncome)}</p>
              </div>
              <div>
                <p class="text-slate-400 text-[10px]">Pengeluaran</p>
                <p class="font-bold text-apple-red">-${formatRupiah(totalExpense)}</p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <button onclick="openTransactionModal('expense')" class="py-3 bg-red-50 text-apple-red rounded-2xl font-semibold text-xs border border-red-100 shadow-sm flex items-center justify-center space-x-2 press-effect">
              <i data-lucide="minus-circle" class="w-4 h-4"></i>
              <span>Catat Pengeluaran</span>
            </button>
            <button onclick="openTransactionModal('income')" class="py-3 bg-emerald-50 text-apple-green rounded-2xl font-semibold text-xs border border-emerald-100 shadow-sm flex items-center justify-center space-x-2 press-effect">
              <i data-lucide="plus-circle" class="w-4 h-4"></i>
              <span>Catat Pemasukan</span>
            </button>
          </div>

          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Target Tabungan</span>
                <h4 class="text-xs font-bold text-slate-800">${appState.settings.savingsName}</h4>
              </div>
              <button onclick="openSavingsModal()" class="px-2.5 py-1 bg-blue-50 text-apple-blue font-semibold text-xs rounded-xl press-effect flex items-center space-x-1">
                <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                <span>Kelola Target</span>
              </button>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
              <div class="h-full bg-apple-blue transition-all duration-500" style="width: ${savingsPercent}%"></div>
            </div>
            <div class="flex justify-between text-[10px] text-slate-400">
              <span>Progres: ${savingsPercent}%</span>
              <span>${formatRupiah(appState.settings.savingsCurrent)} dari ${formatRupiah(appState.settings.savingsTarget)}</span>
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Riwayat Transaksi</h3>
              <span class="text-xs text-slate-400">${appState.transactions.length} Item</span>
            </div>

            ${appState.transactions.length > 0 ? appState.transactions.map(tx => `
              <div class="p-3.5 rounded-2xl bg-white shadow-sm border border-slate-100 space-y-2">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl ${tx.type === 'income' ? 'bg-emerald-50 text-apple-green' : 'bg-red-50 text-apple-red'} flex items-center justify-center">
                      <i data-lucide="${tx.type === 'income' ? 'arrow-up-right' : 'arrow-down-left'}" class="w-5 h-5"></i>
                    </div>
                    <div>
                      <h4 class="text-xs font-bold text-slate-800">${tx.title}</h4>
                      <p class="text-[10px] text-slate-400">${tx.category} • ${tx.date}</p>
                    </div>
                  </div>
                  <div class="text-right flex items-center space-x-2">
                    <span class="text-xs font-bold ${tx.type === 'income' ? 'text-apple-green' : 'text-slate-800'}">
                      ${tx.type === 'income' ? '+' : '-'}${formatRupiah(tx.amount)}
                    </span>
                    <button onclick="requestDeleteTransaction(${tx.id}, '${tx.title.replace(/'/g, "\\'")}')" class="text-slate-300 hover:text-apple-red press-effect">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                  </div>
                </div>

                ${tx.imageUrl ? `
                  <div class="pt-1 flex items-center space-x-2">
                    <img src="${tx.imageUrl}" onclick="viewFullImage('${tx.imageUrl}')" class="h-16 w-16 object-cover rounded-xl border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity" />
                    <span class="text-[10px] text-slate-400 italic">Lihat Foto Nota (Klik untuk perbesar)</span>
                  </div>
                ` : ''}
              </div>
            `).join('') : '<p class="text-xs text-slate-400 italic text-center py-6">Belum ada transaksi.</p>'}
          </div>
        </div>
      `;
    }

    // --- 5. PROGRESS VIEW ---
    function renderProgress(container) {
      const completedTasks = appState.tasks.filter(t => t.completed).length;
      const totalTasks = appState.tasks.length;
      const completionRate = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

      const totalExpenses = appState.transactions.filter(t => t.type === 'expense').reduce((sum, t) => sum + Number(t.amount), 0);

      container.innerHTML = `
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-3">
            <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-1">
              <div class="w-8 h-8 rounded-xl bg-blue-50 text-apple-blue flex items-center justify-center mb-2">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
              </div>
              <p class="text-[10px] text-slate-400 font-medium">Tugas Selesai</p>
              <h3 class="text-xl font-bold text-slate-900">${completedTasks} / ${totalTasks}</h3>
              <p class="text-[10px] text-emerald-500 font-semibold">${completionRate}% terselesaikan</p>
            </div>

            <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-1">
              <div class="w-8 h-8 rounded-xl bg-red-50 text-apple-red flex items-center justify-center mb-2">
                <i data-lucide="trending-down" class="w-5 h-5"></i>
              </div>
              <p class="text-[10px] text-slate-400 font-medium">Total Pengeluaran</p>
              <h3 class="text-base font-bold text-slate-900">${formatRupiah(totalExpenses)}</h3>
              <p class="text-[10px] text-slate-400">Database Record</p>
            </div>
          </div>

          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <h3 class="text-xs font-bold text-slate-800">Aktivitas Produktivitas Mingguan</h3>
            <div class="h-32 flex items-end justify-between px-2 pt-4 border-b border-slate-100">
              ${[40, 65, 30, 85, 95, 50, 70].map((val, idx) => {
                const days = ['S', 'S', 'R', 'K', 'J', 'S', 'M'];
                const isToday = idx === 4;
                return `
                  <div class="flex flex-col items-center space-y-1 flex-1">
                    <div class="w-5 bg-slate-100 rounded-t-lg relative overflow-hidden flex items-end" style="height: 80px;">
                      <div class="w-full ${isToday ? 'bg-apple-blue' : 'bg-slate-300'} rounded-t-lg transition-all duration-500" style="height: ${val}%;"></div>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400">${days[idx]}</span>
                  </div>
                `;
              }).join('')}
            </div>
          </div>

          <div class="p-4 rounded-3xl bg-white shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-center">
              <h3 class="text-xs font-bold text-slate-800">Konsistensi Kebiasaan</h3>
              <button onclick="openHabitModal()" class="text-xs text-apple-blue font-semibold press-effect">+ Habit Baru</button>
            </div>
            <div class="space-y-3">
              ${appState.habits.map(habit => `
                <div class="space-y-1">
                  <div class="flex justify-between text-xs">
                    <span class="font-semibold text-slate-700">${habit.title}</span>
                    <span class="text-slate-400 text-[10px]">🔥 ${habit.streak} Hari Streak</span>
                  </div>
                  <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-full bg-apple-green" style="width: ${Math.min(habit.streak * 15, 100)}%"></div>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        </div>
      `;
    }

    // --- Modal Management Helpers ---
    function openModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.classList.remove('opacity-0');
        const child = modal.firstElementChild;
        if (child) child.classList.remove('translate-y-full', 'scale-95');
      }, 10);
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.add('opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 200);
    }

    function openUniversalAddModal() { openModal('modal-quick-add'); }

    function openTaskModal() {
      document.getElementById('form-task').reset();
      document.getElementById('task-id').value = '';
      document.getElementById('task-modal-title').textContent = 'Tambah Jadwal / Tugas';
      document.getElementById('btn-submit-task').textContent = 'Simpan Aktivitas';
      document.getElementById('task-date').value = getTodayString();
      openModal('modal-add-task');
    }

    function editTask(id) {
      const task = appState.tasks.find(t => Number(t.id) === Number(id));
      if (!task) return;
      
      document.getElementById('task-id').value = task.id;
      document.getElementById('task-modal-title').textContent = 'Edit Jadwal / Tugas';
      document.getElementById('btn-submit-task').textContent = 'Perbarui Aktivitas';
      document.getElementById('task-title').value = task.title;
      document.getElementById('task-category').value = task.category;
      document.getElementById('task-priority').value = task.priority;
      document.getElementById('task-date').value = task.date;
      document.getElementById('task-time').value = task.time;
      openModal('modal-add-task');
    }

    function openNoteModal() {
      document.getElementById('form-note').reset();
      document.getElementById('note-id').value = '';
      document.getElementById('note-modal-title').textContent = 'Catatan Baru';
      
      activeRetainedImages = [];
      activeNewImageFiles = [];
      activeRemovedImages = [];

      setupFolderAssignmentUI(null);
      renderImagePreviewGrid();
      openModal('modal-add-note');
    }

    function editNote(id) {
      const note = appState.notes.find(n => Number(n.id) === Number(id));
      if (!note) return;

      document.getElementById('form-note').reset();
      document.getElementById('note-id').value = note.id;
      document.getElementById('note-modal-title').textContent = 'Edit Catatan';
      document.getElementById('note-title').value = note.title;
      document.getElementById('note-content').value = note.content;
      document.getElementById('note-pin').checked = note.isPinned;

      activeRetainedImages = note.imageUrls ? [...note.imageUrls] : (note.imageUrl ? [note.imageUrl] : []);
      activeNewImageFiles = [];
      activeRemovedImages = [];

      setupFolderAssignmentUI(note.folderId);
      renderImagePreviewGrid();
      openModal('modal-add-note');
    }

    function setupFolderAssignmentUI(targetFolderId) {
      const container = document.getElementById('folder-assignment-container');
      const activeId = targetFolderId !== null ? targetFolderId : appState.currentFolderId;
      const currentFolder = appState.folders.find(f => Number(f.id) === Number(activeId));

      document.getElementById('note-target-folder-id').value = activeId || '';

      if (activeId && currentFolder) {
        container.innerHTML = `
          <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200/60 flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <i data-lucide="folder" class="w-4 h-4 text-amber-600 fill-amber-200"></i>
              <span class="text-xs font-bold text-amber-900">Folder Target: ${currentFolder.name}</span>
            </div>
            <span class="text-[10px] text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md font-semibold">Otomatis Terpilih</span>
          </div>
        `;
      } else {
        container.innerHTML = `
          <label class="block text-xs font-semibold text-slate-500 mb-1">Folder Tujuan</label>
          <select id="note-folder-select" onchange="document.getElementById('note-target-folder-id').value=this.value" class="w-full px-3 py-2.5 rounded-xl bg-slate-100 text-slate-800 text-sm focus:outline-none">
            <option value="">(Tanpa Folder / Root)</option>
            ${appState.folders.map(f => `
              <option value="${f.id}" ${Number(f.id) === Number(activeId) ? 'selected' : ''}>${f.name}</option>
            `).join('')}
          </select>
        `;
      }
      if (window.lucide) lucide.createIcons();
    }

    function previewSelectedImages(event) {
      const files = Array.from(event.target.files);
      if (files.length > 0) {
        activeNewImageFiles = [...activeNewImageFiles, ...files];
      }
      renderImagePreviewGrid();
    }

    function renderImagePreviewGrid() {
      const grid = document.getElementById('image-preview-grid');
      const totalImages = activeRetainedImages.length + activeNewImageFiles.length;

      if (totalImages === 0) {
        grid.classList.add('hidden');
        grid.innerHTML = '';
        return;
      }

      grid.classList.remove('hidden');
      grid.innerHTML = '';

      activeRetainedImages.forEach((url, idx) => {
        const item = document.createElement('div');
        item.className = 'relative h-24 rounded-xl overflow-hidden border border-slate-200 shadow-sm group';
        item.innerHTML = `
          <img src="${url}" class="h-full w-full object-cover" />
          <button type="button" onclick="removeRetainedImage(${idx})" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-apple-red text-white flex items-center justify-center shadow-md press-effect" title="Hapus Gambar">
            <i data-lucide="x" class="w-3.5 h-3.5"></i>
          </button>
        `;
        grid.appendChild(item);
      });

      activeNewImageFiles.forEach((file, idx) => {
        const item = document.createElement('div');
        item.className = 'relative h-24 rounded-xl overflow-hidden border border-slate-200 shadow-sm group';
        
        const reader = new FileReader();
        reader.onload = function(e) {
          item.innerHTML = `
            <img src="${e.target.result}" class="h-full w-full object-cover" />
            <button type="button" onclick="removeNewImageFile(${idx})" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-apple-red text-white flex items-center justify-center shadow-md press-effect" title="Hapus Gambar">
              <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
          `;
          if (window.lucide) lucide.createIcons();
        };
        reader.readAsDataURL(file);
        grid.appendChild(item);
      });

      if (window.lucide) lucide.createIcons();
    }

    function removeRetainedImage(index) {
      const removedUrl = activeRetainedImages[index];
      if (removedUrl) {
        activeRemovedImages.push(removedUrl);
        activeRetainedImages.splice(index, 1);
      }
      renderImagePreviewGrid();
    }

    function removeNewImageFile(index) {
      activeNewImageFiles.splice(index, 1);
      renderImagePreviewGrid();
    }

    function openTransactionModal(type) {
      document.getElementById('form-transaction').reset();
      document.getElementById('tx-type').value = type;
      document.getElementById('tx-image-preview-container').classList.add('hidden');
      document.getElementById('tx-image-preview-element').src = '';
      
      const titleEl = document.getElementById('tx-modal-title');
      const btnEl = document.getElementById('tx-submit-btn');

      if (type === 'income') {
        titleEl.textContent = 'Tambah Pemasukan';
        btnEl.textContent = 'Simpan Pemasukan';
        btnEl.className = 'w-full py-3 bg-apple-green text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2';
      } else {
        titleEl.textContent = 'Tambah Pengeluaran';
        btnEl.textContent = 'Simpan Pengeluaran';
        btnEl.className = 'w-full py-3 bg-apple-red text-white rounded-xl font-semibold text-sm shadow-md press-effect mt-2';
      }
      openModal('modal-add-transaction');
    }

    function previewTransactionImage(event) {
      const file = event.target.files[0];
      const previewContainer = document.getElementById('tx-image-preview-container');
      const previewImg = document.getElementById('tx-image-preview-element');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      } else {
        previewContainer.classList.add('hidden');
      }
    }

    function removeTransactionImage() {
      document.getElementById('tx-image-file').value = '';
      document.getElementById('tx-image-preview-container').classList.add('hidden');
      document.getElementById('tx-image-preview-element').src = '';
    }

    function viewFullImage(url) {
      document.getElementById('full-image-element').src = url;
      openModal('modal-image-viewer');
    }

    function openFolderModal() {
      document.getElementById('form-folder').reset();
      openModal('modal-add-folder');
    }

    function openSavingsModal() {
      document.getElementById('form-savings').reset();
      document.getElementById('savings-name').value = appState.settings.savingsName;
      document.getElementById('savings-target').value = appState.settings.savingsTarget;
      document.getElementById('savings-current').value = appState.settings.savingsCurrent;
      openModal('modal-edit-savings');
    }

    function openHabitModal() {
      document.getElementById('form-habit').reset();
      openModal('modal-add-habit');
    }

    // --- DELETION CONFIRMATION DIALOG CONTROLLER ---
    function openConfirmDeleteModal(itemType, itemName, callbackAction) {
      document.getElementById('confirm-delete-title').textContent = `Hapus ${itemType}?`;
      document.getElementById('confirm-delete-message').textContent = `Apakah Anda yakin ingin menghapus "${itemName}"? Data dan berkas gambar yang terhapus tidak dapat dikembalikan.`;
      pendingDeleteAction = callbackAction;
      openModal('modal-confirm-delete');
    }

    async function executePendingDelete() {
      if (pendingDeleteAction) {
        await pendingDeleteAction();
        pendingDeleteAction = null;
      }
      closeModal('modal-confirm-delete');
    }

    function requestDeleteFolder(id, name) {
      openConfirmDeleteModal('Folder', name, async () => {
        appState.folders = appState.folders.filter(f => Number(f.id) !== Number(id));
        appState.notes = appState.notes.filter(n => Number(n.folderId) !== Number(id));
        switchTab(appState.activeTab);

        await fetch(`./api/folders.php?id=${id}`, { method: 'DELETE' });
      });
    }

    function requestDeleteTask(id, title) {
      openConfirmDeleteModal('Tugas', title, async () => {
        appState.tasks = appState.tasks.filter(t => Number(t.id) !== Number(id));
        switchTab(appState.activeTab);

        await fetch(`./api/tasks.php?id=${id}`, { method: 'DELETE' });
      });
    }

    function requestDeleteNote(id, title) {
      openConfirmDeleteModal('Catatan', title, async () => {
        appState.notes = appState.notes.filter(n => Number(n.id) !== Number(id));
        switchTab(appState.activeTab);

        await fetch(`./api/notes.php?id=${id}`, { method: 'DELETE' });
      });
    }

    function requestDeleteTransaction(id, title) {
      openConfirmDeleteModal('Transaksi', title, async () => {
        appState.transactions = appState.transactions.filter(t => Number(t.id) !== Number(id));
        switchTab(appState.activeTab);

        await fetch(`./api/transactions.php?id=${id}`, { method: 'DELETE' });
      });
    }

    // --- OPTIMISTIC UI INSTANT TOGGLES ---
    function toggleHabit(id) {
      const habit = appState.habits.find(h => Number(h.id) === Number(id));
      if (habit) {
        habit.completedToday = !habit.completedToday;
        if (habit.completedToday) {
          habit.streak += 1;
        } else {
          habit.streak = Math.max(0, habit.streak - 1);
        }
        switchTab(appState.activeTab);
      }

      fetch('./api/habits.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      }).catch(err => console.error('Background habit update failed:', err));
    }

    function toggleTaskStatus(id, currentStatus) {
      const task = appState.tasks.find(t => Number(t.id) === Number(id));
      if (task) {
        task.completed = !currentStatus;
        switchTab(appState.activeTab);
      }

      fetch('./api/tasks.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, completed: !currentStatus })
      }).catch(err => console.error('Background task update failed:', err));
    }

    function togglePinNote(id) {
      const note = appState.notes.find(n => Number(n.id) === Number(id));
      if (note) {
        note.isPinned = !note.isPinned;
        switchTab('notes');
      }

      fetch('./api/notes.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, toggle_pin: true })
      }).catch(err => console.error('Background pin note failed:', err));
    }

    // --- FORM ACTIONS TO PHP BACKEND ---
    async function handleSaveTask(e) {
      e.preventDefault();
      const id = document.getElementById('task-id').value;
      const title = document.getElementById('task-title').value;
      const category = document.getElementById('task-category').value;
      const priority = document.getElementById('task-priority').value;
      const date = document.getElementById('task-date').value;
      const time = document.getElementById('task-time').value;

      try {
        const response = await fetch('./api/tasks.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id || null, title, category, priority, date, time })
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-add-task');
          await fetchAppState();
        }
      } catch (err) {
        alert('Gagal menyimpan tugas: ' + err.message);
      }
    }

    async function handleSaveNote(e) {
      e.preventDefault();
      const id = document.getElementById('note-id').value;
      const folderId = document.getElementById('note-target-folder-id').value;
      const title = document.getElementById('note-title').value;
      const content = document.getElementById('note-content').value;
      const isPinned = document.getElementById('note-pin').checked;

      const formData = new FormData();
      if (id) formData.append('id', id);
      if (folderId) formData.append('folder_id', folderId);
      formData.append('title', title);
      formData.append('content', content);
      formData.append('is_pinned', isPinned ? '1' : '0');

      if (activeRetainedImages.length > 0) {
        formData.append('retained_image_urls', JSON.stringify(activeRetainedImages));
      }

      if (activeRemovedImages.length > 0) {
        formData.append('removed_image_urls', JSON.stringify(activeRemovedImages));
      }

      activeNewImageFiles.forEach((file) => {
        formData.append('images[]', file);
      });

      const saveBtn = document.getElementById('btn-save-note');
      saveBtn.disabled = true;
      saveBtn.innerHTML = '<span>Mengunggah & Menyimpan...</span>';

      try {
        const response = await fetch('./api/notes.php', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-add-note');
          await fetchAppState();
        } else {
          alert(res.message || 'Gagal menyimpan catatan');
        }
      } catch (err) {
        alert('Gagal koneksi ke server PHP: ' + err.message);
      } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<span>Simpan Catatan</span>';
      }
    }

    async function handleSaveTransaction(e) {
      e.preventDefault();
      const type = document.getElementById('tx-type').value;
      const amount = document.getElementById('tx-amount').value;
      const title = document.getElementById('tx-title').value;
      const category = document.getElementById('tx-category').value;
      const imageFile = document.getElementById('tx-image-file').files[0];

      const formData = new FormData();
      formData.append('type', type);
      formData.append('amount', amount);
      formData.append('title', title);
      formData.append('category', category);
      formData.append('date', getTodayString());
      if (imageFile) {
        formData.append('image', imageFile);
      }

      try {
        const response = await fetch('./api/transactions.php', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-add-transaction');
          await fetchAppState();
        }
      } catch (err) {
        alert('Gagal mencatat transaksi: ' + err.message);
      }
    }

    async function handleSaveFolder(e) {
      e.preventDefault();
      const name = document.getElementById('folder-name').value;
      try {
        const response = await fetch('./api/folders.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, parentId: appState.currentFolderId })
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-add-folder');
          await fetchAppState();
        }
      } catch (err) {
        alert('Gagal membuat folder: ' + err.message);
      }
    }

    function navigateToFolder(id) {
      appState.currentFolderId = id;
      switchTab('notes');
    }

    async function handleSaveSavings(e) {
      e.preventDefault();
      const savingsName = document.getElementById('savings-name').value;
      const savingsTarget = document.getElementById('savings-target').value;
      const savingsCurrent = document.getElementById('savings-current').value;
      const depositAmount = document.getElementById('savings-deposit').value;

      try {
        if (depositAmount && Number(depositAmount) > 0) {
          await fetch('./api/settings.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ depositAmount: Number(depositAmount) })
          });
        }

        const response = await fetch('./api/settings.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ savingsName, savingsTarget, savingsCurrent })
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-edit-savings');
          await fetchAppState();
        }
      } catch (err) {
        alert('Gagal menyimpan target tabungan: ' + err.message);
      }
    }

    async function handleSaveHabit(e) {
      e.preventDefault();
      const title = document.getElementById('habit-title').value;
      try {
        const response = await fetch('./api/habits.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ title })
        });
        const res = await response.json();
        if (res.success) {
          closeModal('modal-add-habit');
          await fetchAppState();
        }
      } catch (err) {
        alert('Gagal menambahkan kebiasaan: ' + err.message);
      }
    }

    // Initialize App
    window.onload = function() {
      updateClock();
      fetchAppState();
    };
  </script>
</body>
</html>
