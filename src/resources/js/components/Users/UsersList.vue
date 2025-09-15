<template>
    <div class="container mx-auto px-4 py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Users</h1>
        <router-link 
          to="/users/create" 
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Add User
        </router-link>
        <button 
          @click="logout"
          class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        >
          Logout
        </button>
      </div>
  
      

      <div v-if="loading" class="text-center py-4">
        <span class="text-gray-500">Loading users...</span>
      </div>
  
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>
  
      <div v-if="!loading && users.length > 0" class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ user.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ user.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ user.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <router-link 
                  :to="`/users/${user.id}`"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  View
                </router-link>
                <router-link 
                  :to="`/users/${user.id}/edit`"
                  class="text-green-600 hover:text-green-900"
                >
                  Edit
                </router-link>
                <button 
                  @click="confirmDelete(user)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <div v-if="!loading && users.length === 0" class="text-center py-8">
        <p class="text-gray-500 text-lg">No users found</p>
        <router-link 
          to="/users/create" 
          class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Create First User
        </router-link>
      </div>
  
      <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
          <p class="text-sm text-gray-500 mb-4">
            delete {{ userToDelete?.name }}? 
          </p>
          <div class="flex justify-end space-x-3">
            <button 
              @click="cancelDelete"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
            >
              Cancel
            </button>
            <button 
              @click="deleteUser"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UsersList',
    data() {
      return {
        users: [],
        loading: true,
        error: null,
        showDeleteModal: false,
        userToDelete: null
      }
    },
    async mounted() {
      await this.fetchUsers();
    },
    methods: {
      async fetchUsers() {
        try {
          this.loading = true;
          const token = localStorage.getItem('token');
          const response = await axios.get('/api/users', {
            headers: { Authorization: `Bearer ${token}` }
          });
          this.users = response.data.data;
        } catch (error) {
          this.error = error.response?.data?.message || 'Failed to fetch users';
          if (error.response?.status === 401) {
            this.$router.push('/login');
          }
        } finally {
          this.loading = false;
        }
      },

      async logout () {
        try {
          const token = localStorage.getItem('token');
          await axios.post('/api/logout', {}, {
            headers: { Authorization: `Bearer ${token}` }
          });
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/login');
        } catch (error) {
          this.error = error.response?.data?.message || 'Logout failed';
        }
      },
      confirmDelete(user) {
        this.userToDelete = user;
        this.showDeleteModal = true;
      },
      cancelDelete() {
        this.userToDelete = null;
        this.showDeleteModal = false;
      },
      async deleteUser() {
        try {
          const token = localStorage.getItem('token');
          await axios.delete(`/api/users/${this.userToDelete.id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          
          this.users = this.users.filter(u => u.id !== this.userToDelete.id);
          this.cancelDelete();
        } catch (error) {
          this.error = error.response?.data?.message || 'Failed to delete user';
        }
      }
    }
  }
  </script>