<template>
  <div
    class="modal fade addCartModal"
    id="addCartModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="addCartModal"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body my-row">ahoj</div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      initialized: false,
    };
  },
  methods: {
    showAddToCartModal() {
      const modalElement = document.getElementById("addCartModal");
      if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
      }
    },
  },
  watch: {
    "$store.state.cart"(newCart, oldCart) {
      // Ignore first trigger
      if (!this.initialized) {
        this.initialized = true;
        return;
      }
      let newCount = 0;
      newCart?.forEach((item) => {
        newCount += item.count;
      });
      let oldCount = 0;
      oldCart?.forEach((item) => {
        oldCount += item.count;
      });

      // Trigger only if new items are added
      if (newCart.length > oldCart.length || newCount > oldCount) {
        this.showAddToCartModal();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.cart {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 0;
  gap: 1.5rem;

  &__icon__wrapper {
    position: relative;
  }

  img {
    height: 3.5rem;
  }

  &__text {
    display: flex;
    flex-direction: column;
    font-weight: 100;
    font-size: 1.5rem;
    text-align: right;
    margin-right: 0.6rem;

    span:first-child {
      line-height: 1.2rem;
      margin-bottom: 0.4rem;
    }

    &__white {
      line-height: 1.6rem;
      font-size: 1.4rem;
      color: $white;
    }
  }

  &__number {
    background: $white;
    position: absolute;
    top: -1rem;
    right: -1rem;
    border-radius: 50%;
    height: 2rem;
    width: 2rem;
    font-size: 1.2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 400;
  }
}

@media screen and (max-width: $screen-lg-min - 1px) {
  .cart__text__white {
    display: none;
  }
}

@media screen and (min-width: $screen-xl-min) {
  .cart {
    margin-right: 2rem;

    img {
      height: 4.3rem;
    }
  }
}
</style>
