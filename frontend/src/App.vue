<template>
  <div class="h-[100vh] w-full">
    <Register />
  </div>
</template>

<script lang="ts">
import Register from './pages/Register.vue';

export default {
  name: 'App',

  components: {
    Register
  }
};
</script>