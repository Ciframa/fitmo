<template>
  <div class="verifyEmail">
    <h1>Probíhá ověřování emailu</h1>
    <font-awesome-icon :icon="['fa', 'spinner']" size="3x" />
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },

  methods: {},
  created() {
    let splittedRoute = this.$route.path.split("/");
    let route = splittedRoute[splittedRoute.length - 1];
    console.log(
      "Checkni v db jestli tahle route: " +
        route +
        "je v db a je míň 24 hodin od registrace / poslání emailu"
    );
  },
};
</script>
<style lang="scss">
@keyframes rotate {
  to {
    transform: rotate(360deg);
  }
}

.verifyEmail {
  display: flex;
  justify-content: center;
  flex-direction: column;
  h1 {
  }
  svg {
    -webkit-animation: rotate 2s linear infinite;
    width: 5rem;
    margin: 3rem auto 5rem auto;
  }
}
</style>
