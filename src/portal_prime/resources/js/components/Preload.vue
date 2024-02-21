<template>
    <div v-if="!conteudoCarregado" class="flex items-center justify-center h-screen">
      <div class="loader"></div>
    </div>
  </template>

  <script>
  export default {
    props: ['conteudoCarregado']
  };
  </script>

  <style>
  /* Estilos do preload */
  .loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>
