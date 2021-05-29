<template>
  <div class="vue-app">
    <b>I'm a Vue App.</b>
    <br />
    <button @click="count += 1">{{ count }} clicks</button>
  </div>
</template>

<script>
  import { defineComponent, ref } from 'vue';

  export default defineComponent({
    setup: () => {
      const count = ref(0);
      return { count };
    },
  });
</script>
