<x-app-layout>
  <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit User</h1>

  <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white shadow rounded p-6">
    @include('admin.users.form', ['user' => $user])
  </form>
</x-app-layout>
