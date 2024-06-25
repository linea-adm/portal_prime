<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login de Cliente</h2>
        <form @submit.prevent="login">
          <div class="mb-4">
            <label for="email" class="block text-gray-700">E-mail:</label>
            <input type="email" v-model="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
          </div>
          <div class="mb-4">
            <label for="password" class="block text-gray-700">Senha:</label>
            <input type="password" v-model="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
          </div>
          <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Login</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        email: '',
        password: ''
      };
    },
    methods: {
      login() {
        axios.post('/login', {
          email: this.email,
          password: this.password
        }).then(response => {
          // Redirecionar após login bem-sucedido
          this.$router.push({ name: 'programarEntregas' });
        }).catch(error => {
          console.error('Erro no login:', error);
        });
      }
    }
  };
  </script>
  
  <style scoped>
  </style>
  