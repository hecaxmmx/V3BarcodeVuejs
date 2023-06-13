<template>
  <footer class="footer">
    <div class="content has-text-centered">
      <p>Build v{{ process.env.VUE_APP_VERSION_NUMBER }}</p>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: firebrick;
  color: white;
  text-align: center;
}
</style>
