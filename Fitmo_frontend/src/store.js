import { createStore } from "vuex";

export default createStore({
  state: {
    paymentType: {},
    deliveryType: {},
    cart: [],
    user: {},
  },
  mutations: {
    updatePaymentType(state, newPaymentType) {
      state.paymentType = newPaymentType;
    },
    updateDeliveryType(state, newDeliveryType) {
      state.deliveryType = newDeliveryType;
    },
    updateCart(state, newCart) {
      state.cart = newCart;
    },
    updateUser(state, newUser) {
      state.user = newUser;
    },
  },
});
