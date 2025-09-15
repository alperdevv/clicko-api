<template>
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create User</h1>
  
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {{ error }}
        </div>
  
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ success }}
        </div>
  
        <form @submit.prevent="createUser" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input 
              v-model="form.name"
              type="text" 
              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
              required
            >
          </div>
  
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input 
              v-model="form.email"
              type="email" 
              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
              required
            >
          </div>
  
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input 
              v-model="form.password"
              type="password" 
              class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
              required
            >
          </div>
  
          <div class="flex items-center justify-between">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Create User
            </button>
            
            <router-link 
              to="/userslist"
              class="text-blue-500 hover:text-blue-800"
            >
              Back to Users List
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserCreate',
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: ''
        },
        error: null,
        success: null
      }
    },
    methods: {
      async createUser() {
        try {
          const token = localStorage.getItem('token');
          
          await axios.post('/api/users', this.form, {
            headers: { Authorization: `Bearer ${token}` }
          });
          
          this.success = 'User created!';
          this.form = {
            name: '',
            email: '',
            password: ''
          };
          
         this.$router.push('/userslist');
          
        } catch (error) {
          this.error = 'Cant create the user';
          console.error('Create error:', error.response?.data);
        }
      }
    }
  }
  </script>