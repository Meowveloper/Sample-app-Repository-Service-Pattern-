<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Brain On</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-500 to-yellow-100 h-screen flex flex-col items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-96 md:w-2/3 lg:w-1/2 xl:w-1/3 text-center mb-10">

            <svg width="177" height="157" class=" items-center justify-center  mx-auto" viewBox="0 0 177 157" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="logo" d="M130.474 114.8L129.524 116.424L116.601 108.648L115.312 107.872L114.023 108.648L101.101 116.424L100.151 114.8L115.31 105.675C117.973 107.281 120.635 108.882 123.295 110.481L123.306 110.488C125.697 111.925 128.085 113.361 130.474 114.8ZM61.4006 47.1731L74.7462 39.3432L75.655 40.9545L60.0997 50.083C54.914 47.0455 49.7495 44.0077 44.5858 40.9689L45.5153 39.3376L58.8704 47.1731L60.1355 47.9153L61.4006 47.1731ZM104.817 37.5767V24.2884H106.624V37.5767V39.0087L107.859 39.7332L120.545 47.1733L121.812 47.9164L123.078 47.1713L133.701 40.9189L134.932 40.1938V38.7644V18.8542V17.424L133.7 16.6991L111.835 3.84486L110.57 3.10118L109.304 3.84304L91.0766 14.524L73.0549 3.94862L71.8422 3.23695L70.6042 3.90374L55.7529 11.9027L51.8398 14.0103L55.673 16.2599L69.4957 24.3719L68.5674 26.0009L51.1556 15.7896L49.9407 15.0771L48.7018 15.7469L27.3187 27.3088L26.0077 28.0176V29.5079V50.1515V51.5817L27.2406 52.3066L40.6284 60.1787L39.7156 61.7971L26.5081 54.0388L25.1407 53.2356L23.8314 54.1303L4.30464 67.4736L3.21512 68.2181V69.5377V91.1943V92.5165L4.30805 93.2607L24.0067 106.674L25.315 107.565L26.6799 106.763L39.2279 99.3929L40.4617 98.6681V97.2372V84.7672V83.3343L39.2253 82.61L25.843 74.7713L26.7545 73.1554L40.1 80.9854L41.3651 81.7276L42.6302 80.9854L55.9758 73.1554L56.8892 74.7749L43.5014 82.6471L42.2686 83.372V84.8021V97.2721V98.7041L43.5038 99.4286L56.1893 106.869L57.4543 107.611L58.7191 106.869L72.0647 99.0386L72.9781 100.658L59.5903 108.53L58.3575 109.255V110.685V123.974H56.5506V110.685V109.253L55.3154 108.529L42.6299 101.089L41.3651 100.347L40.1003 101.089L27.4149 108.529L26.1796 109.253V110.685V128.255V129.812L27.5766 130.499L46.7596 139.93L47.9673 140.523L49.128 139.842L68.4556 128.499L90.6019 141.484L91.8492 142.216L93.1052 141.499L115.913 128.489L153.1 150.321L156.866 152.532V148.165V112.362V110.93L155.63 110.205L126.948 93.3899L127.861 91.7723L153.376 106.729L154.574 107.431L155.803 106.786L172.236 98.1582L173.573 97.4558V95.9448V71.4239V69.9926L172.339 69.268L147.515 54.6941L148.442 53.0662L169.807 65.6155L173.573 67.8277V63.4599V40.0568V38.6782L172.408 37.9425L159.344 29.699L158.057 28.8869L156.744 29.6571L123.948 48.9036L122.713 49.6281V51.0597V63.5297V64.9618L123.948 65.6862L150.765 81.4132L149.837 83.0416L123.074 67.3463L121.809 66.6044L120.545 67.3465L94.3485 82.7157L93.1136 83.4402V84.872V98.1953H91.3068V84.9069V83.4768L90.074 82.7519L76.6861 74.8797L77.5996 73.2602L90.9451 81.0901L92.2102 81.8324L93.4753 81.0902L119.671 65.7209L120.906 64.9964V63.5647V51.0947V49.6748L119.687 48.9475L107.036 41.4026L105.766 40.6451L104.49 41.3935L91.1293 49.2323L90.2027 47.6004L103.585 39.7318L104.817 39.0069V37.5767Z" fill="#006CA5" stroke="#FFC508" stroke-width="5"/>
            </svg>
            <br>
            <span class="text-yellow-600">𝘽𝙍𝘼𝙄𝙉 𝙊𝙉</span>




        <h2 class="text-2xl font-semibold from-blue-500 to-yellow-100 mb-4">WELCOME TO 𝘽𝙍𝘼𝙄𝙉 𝙊𝙉</h2> <br>
        <form id="loginForm" action="{{ url('admin/login') }}" method="POST" class="space-y-4">
            @if(session('error'))
                <div class="text-red-600">
                    {{ session('error') }}
                </div>
            @endif
            @csrf
            <div>
                {{--  <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>  --}}
                <input type="text" id="email" name="email" placeholder="Email" class="mt-1 p-3 w-full rounded-md border border-gray-300 focus:outline-none focus:border-blue-500 text-gray-800" >
            </div>
            @error('email')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            <div>
                {{--  <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>  --}}
                <input type="password" id="password" name="password" placeholder="Password" class="mt-1 p-3 w-full rounded-md border border-gray-300 focus:outline-none focus:border-blue-500 text-gray-800" >
            </div>
            @error('password')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300">LOGIN</button>
        </form>
        <br>

    </div>
</body>
</html>
