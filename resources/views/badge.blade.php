@if($user = request()->user())
<div class="fixed bottom-0 right-0 w-full md:w-auto md:m-4 bg-gray-300 border border-gray-400 p-2 flex md:rounded-tl-full md:rounded-bl-full items-center justify-around shadow-lg">
  <a href="{{ route('home') }}">
    <img src="{{ $user->avatar }}" alt="avatar" class="w-12 rounded-full mr-4" title="{{ $user->email }}">
  </a>
  <p>
    <a href="{{ route('logout') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded h-8">Log out</a>
  </p>
</div>
@endif
