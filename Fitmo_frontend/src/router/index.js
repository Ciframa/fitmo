import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/Home.vue";
import Product from "@/views/Product.vue";
import Category from "@/views/Category.vue";
import Registration from "@/views/Registration.vue";

import PaymentDelivery from "@/views/buySteps/PaymentDelivery.vue";
import Cart from "@/views/buySteps/Cart.vue";
import CustomerInfo from "@/views/buySteps/CustomerInfo.vue";
import OrderConfirmation from "@/views/buySteps/OrderConfirmation.vue";

import ChangeReturn from "@/views/info/ChangeReturn.vue";
import Contact from "@/views/info/Contact.vue";
import OrderStatus from "@/views/info/OrderStatus.vue";
import PaymentDeliveryInfo from "@/views/info/PaymentDelivery.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/:categoryname+",
    name: "Category",
    component: Category,
    props: true,
  },
  {
    path: "/produkt",
    name: "Product",
    component: Product,
  },
  {
    path: "/registrace",
    name: "Registration",
    component: Registration,
  },
  {
    path: "/objednavka/doprava-a-platba",
    name: "PaymentDelivery",
    component: PaymentDelivery,
  },
  {
    path: "/objednavka/kosik",
    name: "Cart",
    component: Cart,
  },
  {
    path: "/objednavka/informace",
    name: "CustomerInfo",
    component: CustomerInfo,
  },
  {
    path: "/objednavka/potvrzeni",
    name: "OrderConfirmation",
    component: OrderConfirmation,
  },
  {
    path: "/info/stav-objednavky",
    name: "stav-objednavky",
    component: OrderStatus,
  },
  {
    path: "/info/vymena-a-vraceni-zbozi",
    name: "vymena-a-vraceni-zbozi",
    component: ChangeReturn,
  },
  {
    path: "/info/kontakt",
    name: "kontakt",
    component: Contact,
  },
  {
    path: "/info/doprava",
    name: "doprava",
    component: PaymentDeliveryInfo,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return { left: 0, top: 0, behavior: "smooth" };
  },
});
export default router;
