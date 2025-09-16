<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
      <div class="max-w-md w-full space-y-8">

        <div class="flex justify-center mb-6">
          <img 
            src="https://clicko.es/img/header.webp?version=2.7" 
            alt="Clicko Logo" 
            class="h-16 w-auto"
          >
        </div>
        
        <div>
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Sign in to your account
          </h2>
        </div>
        
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>
        
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ success }}
        </div>
  
        <form class="mt-8 space-y-6" @submit.prevent="login">
          <div class="rounded-md shadow-sm -space-y-px">
            <div>
              <label for="email" class="sr-only">Email address</label>
              <input 
                id="email" 
                v-model="form.email"
                name="email" 
                type="email" 
                required 
                class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                placeholder="Email address"
              >
            </div>
            <div>
              <label for="password" class="sr-only">Password</label>
              <input 
                id="password" 
                v-model="form.password"
                name="password" 
                type="password" 
                required 
                class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                placeholder="Password"
              >
            </div>
          </div>
  
          <div>
            <button 
              type="submit" 
              :disabled="loading"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              <span v-if="loading">Logging in...</span>
              <span v-else>Sign in</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Login',
    data() {
      return {
        form: {
          email: '',
          password: ''
        },
        loading: false,
        error: null,
        success: null
      }
    },
    methods: {
      async login() {
        this.loading = true;
        this.error = null;
        this.success = null;
  
        try {
          const response = await axios.post('/api/login', this.form);
          

          localStorage.setItem('token', response.data.token);
          localStorage.setItem('user', JSON.stringify(response.data.user));
          
          this.success = 'Login successful! Redirecting...';
          
          setTimeout(() => {
            this.$router.push('/userslist');
          }, 1000);
          
        } catch (error) {
          this.error = error.response?.data?.message || 'Login failed';
        } finally {
          this.loading = false;
        }
      }
    }
  }
  </script>