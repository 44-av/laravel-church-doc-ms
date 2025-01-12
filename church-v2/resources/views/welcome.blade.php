<!DOCTYPE html>
<html lang="en">

<head>
    <title>St. Michael the Archangel Parish Church</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <div class="navbar bg-[#467292] shadow-md sticky top-0 z-10">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden" id="menu-toggle">
                    <i class='bx bx-menu text-white text-2xl'></i>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-[#467292] rounded-box z-[1] mt-3 w-52 p-2 shadow transition-all duration-300 ease-in-out opacity-0 transform -translate-y-2 hidden"
                    id="mobile-menu-content">
                    <li><a href="/" class="text-white">Our Parish</a></li>
                    <li><a href="#services" class="text-white">Services</a></li>
                    <li><a href="#donate" class="text-white">Donate</a></li>
                    <li><a href="#events" class="text-white">Events</a></li>
                    <li><a href="#contact-us" class="text-white">Contact Us</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="w-10 h-10 mr-2">
                <p class="text-sm md:text-2xl text-white">St. Michael the Archangel Parish Church</p>
            </a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="/" class="text-white">Our Parish</a></li>
                <li><a href="#services" class="text-white">Services</a></li>
                <li><a href="#donate" class="text-white">Donate</a></li>
                <li><a href="#events" class="text-white">Events</a></li>
                <li><a href="#contact-us" class="text-white">Contact Us</a></li>
            </ul>
        </div>
    </div>
<!-- Landing Page -->
    <div class="hero bg-base-200 min-h-screen flex items-center justify-center"
        style="background-image: url('{{ asset('assets/img/church.jpg') }}'); background-size: cover;">
        <div class="hero-content text-center bg-white bg-opacity-80 p-8 rounded-lg shadow-lg m-3">
            <div class="max-w-8xl">
                <h1 class="text-5xl font-bold text-gray-800">Welcome to St. Michael the Archangel Parish Church</h1>
                <h1 class="py-6 text-gray-700">
                    We are a community of believers dedicated to spreading the love of God and serving our neighbors.
                </h1>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>

                <section class="mt-8 bg-[#467292] p-6 rounded-lg shadow-md w-2/4 mx-auto">
                    <h2 class="text-2xl font-bold mb-4 text-white uppercase">Announcements</h2>
                    <div class="card-body p-0">
                        @foreach ($announcements as $announcement)
                            <div class="bg-purple-100 p-4 rounded-lg mb-4">
                                <h3 class="text-xl font-semibold text-cornflowerblue">{{ $announcement->title }}</h3>
                                @if (strpos($announcement->content, 'Schedule:') !== false)
                                    <ul class="list-disc list-inside text-cornflowerblue mt-2">
                                        @foreach (explode("\n", $announcement->content) as $line)
                                            @if (trim($line) !== '')
                                                <li>{{ $line }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-cornflowerblue mt-2">{{ $announcement->content }}</p>
                                @endif
                                @if ($announcement->assigned_priest)
                                    <hr class="my-4">
                                    <h3 class="text-xl font-semibold text-cornflowerblue">Assigned Priest</h3>
                                    <p class="text-cornflowerblue mt-2">REV. FR. {{ $announcement->priest->first_name }}
                                        {{ $announcement->priest->middle_name }}
                                        {{ $announcement->priest->last_name }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
<!-- History Contents -->
 <section id="donate" class="height[100vh]">
    <div class="container mx-auto px-4 py-12">
        <div class="hero min-h-screen flex flex-col items-center">
            <img src="{{ asset('assets/img/church2.jpg') }}" class="w-full max-w-3xl rounded-lg shadow-2xl mb-8" />
            <div class="max-w-3xl text-center bg-white bg-opacity-80 p-8 rounded-lg shadow-lg m-3">
                <h2 class="text-3xl font-bold mb-6 text-gray-800 uppercase">History</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    The church was founded in Basey in 1591 under the bishopric of Cebu and in 1656 it became a
                    visita of Dagami. The Jesuits were the first religious order who tended to the spiritual needs of
                    the
                    inhabitants. Accounts stated that they built the stone church in the late 17th century.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    However, the Jesuits left upon their suppression in 1768. They were replaced by the Augustinians
                    and later by the Franciscans in 1795.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    The church was repaired in 1845 by Franciscan friar Domingo de Madrid. The convent and the bell
                    tower were also erected in that same year.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed mb-12">
                    Throughout its history, the church played many roles in the lives of the people in Basey: as a
                    fortress and a mission station and center of evangelization in Spanish colonial times and evacuation
                    site
                    towards the conclusion of the Second World War.
                </p>
                <h2 class="text-3xl font-bold mb-6 text-gray-800 uppercase">Architecture</h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    The massive bell tower is erected in the gospel side of and foregrounds the church. Its polygonal
                    floor
                    plan makes the edifice look nearly like cylindrical in shape. Made of three tiers, its second level
                    is
                    having rectilinear and dormer windows and the uppermost one has decorative pointed arches,
                    rectangular
                    apertures, and capped with a multi-gabled roof.
                </p>
            </div>
            <div class="flex items-center justify-center bg-[#467292] p-4 rounded-lg shadow-md w-2/4 mx-auto" id="donate">
                <img src="{{ asset('assets/img/logo.png') }}" class="w-24 h-24 rounded-full mr-2" />
                <p class="text-white text-lg leading-relaxed">
                    St. Michael the Archangel Parish Church
                </p>
                <a href="{{ route('login') }}"
                    class="text-white text-lg bg-[#FF7474] px-4 py-2 rounded-md leading-relaxed m-4">
                    Online Giving
                </a>
            </div>
        </div>
    </div>
 </section>

<!-- Services -->
    <section class="bg-white p-8 rounded-lg shadow-md mt-8 pt-20" id="services">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="card-body p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Lent</h1>
                <img src="{{ asset('assets/img/lent.jpg') }}" alt="lent" class="w-full h-48 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-2 text-justify">
                    Lent is the solemn Christian religious observance in the liturgical year commemorating the
                    40 days Jesus spent fasting in the desert and enduring temptation by Satan, according to the
                    Gospels of Matthew, Mark and Luke, before beginning his public ministry.
                </p>
            </div>
            <div class="card-body p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Holy Week</h1>
                <img src="{{ asset('assets/img/holyweek.jpg') }}" alt="holyweek"
                    class="w-full h-48 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-2 text-justify">
                    Holy Week is the most sacred week in the liturgical year in Christianity. For all Christian
                    traditions, it is a moveable observance. In Eastern Christianity, which also calls it Great
                    Week,
                    it is the week following Great Lent and Lazarus Saturday, starting on the evening of Palm Sunday
                    and concluding on the evening of Great Saturday.
                </p>
            </div>
            <div class="card-body p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Advent</h1>
                <img src="{{ asset('assets/img/advent.jpg') }}" alt="advent"
                    class="w-full h-48 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-2 text-justify">
                    Advent is a season observed in most Christian denominations as a time of expectant waiting and
                    preparation for both the celebration of the Nativity of Christ at Christmas and the return of
                    Christ at the Second Coming.
                </p>
            </div>
            <div class="card-body p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Christmas</h1>
                <img src="{{ asset('assets/img/christmas.jpg') }}" alt="christmas"
                    class="w-full h-48 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-2 text-justify">
                    Christmas is an annual festival commemorating the birth of Jesus Christ, observed primarily on
                    December 25. It is a central part of the liturgical year in Christianity, following
                    the Nativity Fast and Christmastide. The traditional Christmas narrative, known as the Nativity
                    of Jesus, tells of Jesus's birth in Bethlehem. The date of Jesus's birth is debated, but the
                    church fixed it as December 25 in the early fourth century. Most Christians celebrate on the
                    Gregorian calendar, while some Eastern Christian Churches celebrate on the older Julian
                    calendar.
                </p>
            </div>
        </div>
    </section>
<!-- Events -->
    <section class="bg-white p-8 rounded-lg shadow-md mt-8 pt-20" id="events">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Events</h2>
        <div class="flex flex-wrap gap-6">
            <div class="card-body w-full sm:w-1/2 lg:w-1/3 p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Rosary</h1>
                <img src="{{ asset('assets/img/rosary.jpg') }}" alt="rosary"
                    class="w-full h-64 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-4">
                    The rosary is a Catholic devotion in which we ask for the intercession of Mary, the Mother of
                    God, in drawing closer to her divine Son, Jesus. When praying the rosary, you use a string of
                    beads to keep track of your progress through the prayers.
                </p>
                <h2 class="text-lg font-semibold mb-2 text-gray-800">Steps to Pray the Rosary:</h2>
                <ol class="list-decimal list-inside text-gray-700 mb-4">
                    <li>Make the Sign of the Cross.</li>
                    <li>Recite the "Our Father".</li>
                    <li>Recite ten "Hail Marys" while meditating on the Mystery.</li>
                    <li>Recite the "Glory Be" and the "O My Jesus" prayer requested by Mary at Fatima.</li>
                    <li>Meditate on the Mysteries, which include the Joyful, Sorrowful, Glorious, and Luminous
                        Mysteries.</li>
                </ol>
            </div>
            <div class="card-body w-full sm:w-1/2 lg:w-1/3 p-0">
                <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Fiestas</h1>
                <img src="{{ asset('assets/img/fiestas.jpg') }}" alt="fiestas"
                    class="w-full h-64 object-cover mb-4">
                <p class="text-gray-700 leading-relaxed mb-4">
                    The feast celebration in Basey, Samar is known as the Kawayan-Banigan Festival. It takes place
                    every
                    last week of September and revolves around two key elements: the banig (a traditional woven mat)
                    and the kawayan (bamboo), representing the local crafts industry. The town's patron saint, St.
                    Michael the Archangel, is honored during this festive occasion, which falls on September 29.
                </p>
            </div>
        </div>
        <div class="card-body p-0">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 uppercase">Religious Education Classes</h1>
            <img src="{{ asset('assets/img/catechism.jpg') }}" alt="catechism"
                class="w-full h-full object-cover mb-4">
            <p class="text-gray-700 leading-relaxed mb-4">
                A catechism class is a structured learning experience focused on religious instruction, primarily
                within the Christian faith. It aims to deepen understanding of Christian doctrine, values, and
                practices, often in preparation for sacraments like Baptism, Communion, or Confirmation. Catechism
                classes typically involve studying sacred texts, learning prayers, and discussing faith-related
                topics. They are designed to foster spiritual growth and develop a personal relationship with God.
            </p>
        </div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4208.226839335605!2d125.07008719999999!3d11.2809598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33087aef09bc178b%3A0x84403690486c226a!2sSt%20Michael%20The%20Archangel%20Parish%20Church!5e1!3m2!1sen!2sph!4v1731461882555!5m2!1sen!2sph"
            class="w-full h-full md:h-screen my-4" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        <iframe class="w-full h-full md:h-screen" src="https://www.youtube.com/embed/sR1IYyH_pN0?si=UIde8hFBiVm48xq0"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </section>
<!-- Footers -->
    <footer class="footer bg-base-200 text-base-content p-10" id="contact-us">
        <nav>
            <h6 class="footer-title">Contact</h6>
            <p>Phone: 0968 - 721 - 8162</p>
            <p>Email: stmichaelthearcanghelparish@gmail.com</p>
        </nav>
        <nav>
            <h6 class="footer-title">Address</h6>
            <p>Brgy. Mercado, Poblacion</p>
            <p>Basey, Samar</p>
        </nav>
        <nav>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3912.733914112191!2d125.06521628149133!3d11.280959760279904!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33087aef09bc178b%3A0x84403690486c226a!2sSt%20Michael%20The%20Archangel%20Parish%20Church!5e0!3m2!1sen!2sph!4v1732831273826!5m2!1sen!2sph"
                width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </nav>
    </footer>
    <footer class="footer footer-center bg-base-200 text-base-content p-4">
        <aside>
            <p class="font-bold">Copyright &copy; {{ date('Y') }} - All right reserved by St. Michael the Arcanghel
                Parish
                Church</p>
        </aside>
    </footer>
    </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu-content');

        menuToggle.addEventListener('click', () => {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.remove('opacity-0', '-translate-y-2');
                }, 10);
            } else {
                mobileMenu.classList.add('opacity-0', '-translate-y-2');
                mobileMenu.addEventListener('transitionend', () => {
                    mobileMenu.classList.add('hidden');
                }, {
                    once: true
                });
            }
        });
    </script>
</body>

</html>
