<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Best Available Seat</title>
    <link rel="stylesheet" href="css/app.css">
    @livewireStyles
</head>
<body>
  <div class="min-h-screen bg-gray-200">
    <h1 class="text-center text-xl text-cyan-700 md:text-4xl pt-4">Best Available Seat</h1>
    <div class="absolute top-16 bottom-16 left-8 right-8 md:inset-32 bg-white rounded">
      <div class="bg-white rounded-t">
        <nav class="flex flex-col bg-cyan-100 sm:flex-row rounded-t">
          <a
            href="{{ route('find-a-seat') }}"
            class="py-4 px-6 block hover:text-cyan-500 focus:outline-none {{ request()->is('seats') ? 'text-cyan-600 border-b-2 font-medium border-cyan-500' : 'text-cyan-500' }}">
            Find A Seat
          </a>
          <a
            href="{{ route('generate-venue') }}"
            class="py-4 px-6 block hover:text-cyan-500 focus:outline-none {{ request()->is('venue') ? 'text-cyan-600 border-b-2 font-medium border-cyan-500' : 'text-cyan-500' }}">
            Generate A Venue
          </a>
          <a
            href="{{ route('docs') }}"
            class="py-4 px-6 block hover:text-cyan-500 focus:outline-none {{ request()->is('docs') ? 'text-cyan-600 border-b-2 font-medium border-cyan-500' : 'text-cyan-500' }}">
            API Documentation
          </a>
        </nav>
        <div class="p-8 text-gray-700">
          {{ $slot ?? '' }}
          @yield('content')
        </div>
      </div>
    </div>
    <p class="absolute bottom-2 md:bottom-10 text-center w-full text-cyan-700">Ryan C. Durham - March 2021</p>
  </div>
  @livewireScripts
</body>
</html>
