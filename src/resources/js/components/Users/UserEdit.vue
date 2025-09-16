<template>
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>
  
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {{ error }}
        </div>
  
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ success }}
        </div>
  
        <form v-if="user" @submit.prevent="updateUser" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
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
              placeholder="Type the new password"
            >
          </div>
  
          <div class="flex items-center justify-between">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Update User
            </button>
            
            <router-link 
              to="/userslist"
              class="text-blue-500 hover:text-blue-800"
            >
              Back to Users
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserEdit',
    data() {
      return {
        user: null,
        form: {
          name: '',
          email: '',
          password: ''
        },
        error: null,
        success: null
      }
    },
    async mounted() {
      await this.fetchUser();
    },
    methods: {
      async fetchUser() {
        try {
          const userId = this.$route.params.id;
          const token = localStorage.getItem('token');
          
          const response = await axios.get(`/api/users/${userId}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          
          this.user = response.data.data || response.data;
          this.form.name = this.user.name;
          this.form.email = this.user.email;
          
        } catch (error) {
          this.error = 'Cant load the user, check logs';
        }
      },
      async updateUser() {
        try {
          const token = localStorage.getItem('token');
          const userId = this.$route.params.id;
          
          const data = {
            name: this.form.name,
            email: this.form.email,
            password: this.form.password
          };
          
          
          await axios.put(`/api/users/${userId}`, data, {
            headers: { Authorization: `Bearer ${token}` }
          });
          
          this.success = 'Ready!';
          
          setTimeout(() => {
            this.$router.push('/users');
          }, 1000);
          
        } catch (error) {
          this.error = 'Cant update the user, check logs';
        }
      }
    }
  }
  </script>
