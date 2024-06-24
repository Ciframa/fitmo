<template>
  <div>
    <div class="row actions">
      <select v-model="this.activeAction">
        <option value="all">Všechny</option>
        <option value="new">Nové</option>
        <option value="confirmed">Potvrzené</option>
        <option value="shipped">Odeslané</option>
        <option value="delivered">Doručené</option>
        <option value="cancelled">Zrušené</option>
      </select>
      <input
        type="submit"
        value="Použít"
        class="btn-yellow"
        :onClick="provideAction(activeAction)"
      />
    </div>
    <table class="collapsed-border">
      <thead>
        <tr>
          <div></div>
          <th>Akce</th>
          <th>Status</th>
          <th>Celková částka</th>
          <th>Delivery</th>
          <th>Delivery Price</th>
          <th>Payment</th>
          <th>Payment Price</th>
          <th>Fakturační adresa</th>
          <th>Dodací adresa</th>
          <th>Kdo objednal</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="order in orders" :key="order.id">
          <tr>
            <td>
              <input type="checkbox" v-model="order.isActive" />
            </td>
            <td
              @click="
                {
                  order.showProducts = !order.showProducts;
                }
              "
            >
              <font-awesome-icon
                :rotation="order.showProducts ? 180 : 0"
                v-if="order.products.length > 0"
                :icon="['fa', 'angle-down']"
                :size="'2x'"
              />
            </td>
            <td>{{ order.status }}</td>
            <td>{{ order.total_price }}</td>
            <td>{{ order.delivery_type.name }}</td>
            <td>{{ order.delivery_price }}</td>
            <td>{{ order.payment_type.name }}</td>
            <td>{{ order.payment_price }}</td>
            <td>
              {{
                order.billing_address.street +
                ", " +
                order.billing_address.city +
                " " +
                order.billing_address.zip +
                " - " +
                order.billing_address.country
              }}
            </td>
            <td>
              {{
                order.delivery_address.street +
                ", " +
                order.delivery_address.city +
                " " +
                order.delivery_address.zip +
                " - " +
                order.delivery_address.country
              }}
            </td>
            <td style="display: flex; flex-direction: column">
              <p>{{ order.user.name }}</p>
              <p>{{ order.user.email }}</p>
              <p>{{ order.user.prePhone }}{{ order.user.phone }}</p>
            </td>
          </tr>
          <tr v-if="order.showProducts">
            <th>Název</th>
            <th>Počet</th>
            <th>Cena</th>
            <th>Cena se slevou</th>
            <th>% sleva</th>
            <th>Barva/varianta</th>
          </tr>
          <tr
            v-if="order.showProducts"
            v-for="product in order.products"
            :key="product.id"
          >
            <td>{{ product.name }}</td>
            <td>{{ product.pivot.product_count }}</td>
            <td>{{ product.price }}</td>
            <td>{{ product.discounted }}</td>
            <td>{{ product.discountedPercent }}</td>
            <td>
              {{
                product.color
                  ? product.color.color_name
                  : product.variant
                  ? product.variant
                  : ""
              }}
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "../../api";
export default {
  data() {
    return {
      orders: [],
      activeAction: "",
    };
  },
  methods: {
    async getOrders() {
      try {
        const response = await axios.get("/api/orders");
        this.orders = response.data;
      } catch (error) {
        console.log(error);
      }
    },

    provideAction(action) {
      console.log(action);
      if (action === "delete") {
        console.log("delete");
      } else if (action === "editState") {
        console.log("edit state");
      }
    },
  },
  created() {
    this.getOrders();
  },
};
</script>
<style lang="scss" scoped>
.actions {
  display: flex;
  gap: 2rem;
  margin-bottom: 2rem;
  .btn-yellow {
    width: unset;
    padding: 0.8rem 2rem;
  }
}

input {
  -webkit-appearance: button;
  appearance: button;
}
/* Apply collapsed border styles */
.collapsed-border {
  border-collapse: collapse;
}

.collapsed-border th,
.collapsed-border td:not(:first-child) {
  border: 1px solid $gray-second;
}
.collapsed-border th,
.collapsed-border td {
  padding: 8px;
}
.collapsed-border th {
  background-color: #f2f2f2;
}
</style>
