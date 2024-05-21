<div id="sidebar" class='active'>
    <div class="sidebar-wrapper active">
        {{-- <div class="sidebar-header">
            <img src="assets/images/logo.svg" alt="" srcset="">
        </div> --}}
        <p class="mt-4">
        <h1 class="text-center"><b>CBT-LPIA</b></h1>
        </p>
        <div class="sidebar-menu">
            <ul class="menu">


                <li class='sidebar-title'>Main Menu</li>



                <li class="sidebar-item {{ $page == 'home' ? 'active' : '' }} ">
                    <a href="/" class='sidebar-link'>
                        <i data-feather="home" width="20"></i>
                        <span>Dashboard</span>
                    </a>

                </li>



                @if (auth()->user()->can('roles-list') ||
                auth()->user()->can('users-list'))
                <li class="sidebar-item {{ $page == 'management_users' ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="user" width="20"></i>
                        <span>Management User</span>
                    </a>

                    <ul class="submenu {{ $page == 'management_users' ? 'active' : '' }}">
                        @can('roles-list')
                        <li>
                            <a href="{{ route('roles.index') }}">Role</a>
                        </li>
                        @endcan
                        @can('users-list')
                        <li>
                            <a href="{{ route('users.index') }}">User</a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endif



                @if (auth()->user()->can('peserta-list') ||
                auth()->user()->can('peserta-create'))
                <li class="sidebar-item {{ $page == 'participans' ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="briefcase" width="20"></i>
                        <span>Master Data Siswa</span>
                    </a>

                    <ul class="submenu {{ $page == 'participans' ? 'active' : '' }}">
                        @can('peserta-list')
                        <li>
                            <a href="{{ route('participant.index') }}">List Data Siswa</a>
                        </li>
                        @endcan
                        @can('peserta-create')
                        <li>
                            <a href="{{ route('participant.create') }}">Tambah Siswa</a>
                        </li>
                        @endcan
                    </ul>

                </li>
                @endif

                @if (auth()->user()->can('guru-list') ||
                auth()->user()->can('guru-create'))
                <li class="sidebar-item {{ $page == 'teachers' ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="users" width="20"></i>
                        <span>Master Data Guru</span>
                    </a>

                    <ul class="submenu {{ $page == 'teachers' ? 'active' : '' }}">
                        @can('guru-list')
                        <li>
                            <a href="{{ route('teachers.index') }}">List Data Guru</a>
                        </li>
                        @endcan
                        @can('guru-create')
                        <li>
                            <a href="{{ route('teachers.create') }}">Tambah Guru</a>
                        </li>
                        @endcan
                    </ul>

                </li>
                @endif

                @if (auth()->user()->can('sesi-list') ||
                auth()->user()->can('sesi-create'))
                <li class="sidebar-item {{ $page == 'sesi' ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="clock" width="20"></i>
                        <span>Sesi Ujian</span>
                    </a>

                    <ul class="submenu {{ $page == 'sesi' ? 'active' : '' }}">
                        @can('sesi-list')
                        <li>
                            <a href="{{ route('exam-session.index') }}">List Sesi Ujian</a>
                        </li>
                        @endcan
                        @can('sesi-create')
                        <li>
                            <a href="{{ route('exam-session.create') }}">Tambah Sesi Ujian</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('mapel-list') ||
                auth()->user()->can('mapel-create'))
                <li class="sidebar-item {{ $page == 'mapel' ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="book" width="20"></i>
                        <span>Master Data Mapel</span>
                    </a>

                    <ul class="submenu {{ $page == 'mapel' ? 'active' : '' }}">
                        @can('mapel-list')
                        <li>
                            <a href="{{ route('mapels.index') }}">List Mata Pelajaran</a>
                        </li>
                        @endcan
                        @can('mapel-create')
                        <li>
                            <a href="{{ route('mapels.create') }}">Tambah Mata Pelajaran</a>
                        </li>
                        @endcan
                    </ul>

                </li>
                @endif

                @if (auth()->user()->can('kelas-list') ||
                auth()->user()->can('jurusan-list'))
                <li class="sidebar-item {{ $page == 'kelas' ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="trello" width="20"></i>
                        <span>Kelas & Kategori</span>
                    </a>

                    <ul class="submenu {{ $page == 'kelas' ? 'active' : '' }}">
                        @can('kelas-list')
                        <li>
                            <a href="{{ route('class.index') }}">Kelas</a>
                        </li>
                        @endcan
                        @can('jurusan-list')
                        <li>
                            <a href="{{ route('majors.index') }}">Kategori</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('soal-list') ||
                auth()->user()->can('soal-create'))
                <li class="sidebar-item {{ $page == 'question' ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="server" width="20"></i>
                        <span>Master Data Soal</span>
                    </a>

                    <ul class="submenu {{ $page == 'question' ? 'active' : '' }}">
                        @can('soal-list')
                        <li>
                            <a href="{{ route('question.index') }}">List Soal Ujian</a>
                        </li>
                        @endcan
                        @can('soal-create')
                        <li>
                            <a href="{{ route('question.create') }}">Tambah Soal Ujian</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('ujian-list') ||
                auth()->user()->can('ujian-create'))
                <li class="sidebar-item {{ $page == 'exam' ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="monitor" width="20"></i>
                        <span>Management Ujian</span>
                    </a>

                    <ul class="submenu {{ $page == 'exam' ? 'active' : '' }}">
                        @can('ujian-list')
                        <li>
                            <a href="{{ route('exam.index') }}">List Ujian</a>
                        </li>
                        @endcan
                        @can('ujian-create')
                        <li>
                            <a href="{{ route('exam.create') }}">Tambah Ujian</a>
                        </li>
                        @endcan
                    </ul>

                </li>
                @endif

                @if (auth()->user()->can('peserta-sesi-list') ||
                auth()->user()->can('peserta-sesi-create'))
                <li class="sidebar-item {{ $page == 'participan_session' ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i data-feather="watch" width="20"></i>
                        <span>Peserta per Sesi</span>
                    </a>

                    <ul class="submenu {{ $page == 'participan_session' ? 'active' : '' }}">
                        @can('peserta-sesi-list')
                        <li>
                            <a href="{{ route('participant-session.index') }}">List Peserta per Sesi</a>
                        </li>
                        @endcan
                        @can('peserta-sesi-create')
                        <li>
                            <a href="{{ route('participant-session.create') }}">Tambah Peserta per Sesi</a>
                        </li>
                        @endcan
                    </ul>

                </li>
                @endif
                @can('hasil-ujian')
                <li class="sidebar-item {{ $page == 'answer' ? 'active' : '' }}">
                    <a href="{{route('answer.index')}}" class='sidebar-link'>
                        <i data-feather="archive" width="20"></i>
                        <span>Hasil Ujian</span>
                    </a>
                </li>
                @endcan
                @can('nilai-ujian')
                <li class="sidebar-item {{ $page == 'nilai' ? 'active' : '' }}">
                    <a href="{{route('nilai.index')}}" class='sidebar-link'>
                        <i data-feather="database" width="20"></i>
                        <span>Report Hasil Ujian</span>
                    </a>
                </li>
                @endcan
                @can('clear-cache')
                <li class="sidebar-item">
                    <a href="{{route('clear-cache.index')}}" class='sidebar-link'>
                        <i data-feather="refresh-cw" width="20"></i>
                        <span>Clear Cache</span>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>