<template>
  <!-- Current select -->
  <select
    :disabled="product.parent_id !== 0"
    v-model="selectedCategory"
    @change="emitSelection"
  >
    <option disabled value="">Select a category</option>
    <option v-for="child in category" :value="child.id" :key="child.id">
      {{ child.name }}
    </option>
  </select>

  <!-- Render next level if selected category has children -->
  <CategorySelect
    v-if="currentCategory?.children?.length"
    :category="currentCategory.children"
    :product="product"
    :index="index"
    @category-selected="$emit('category-selected', $event)"
  />
</template>

<script>
export default {
  name: "CategorySelect",
  props: {
    category: { type: Array, required: true }, // array of categories at this level
    product: { type: Object, required: true },
    index: { type: Number, required: true }, // which level we are at
  },
  data() {
    return {
      selectedCategory: "",
    };
  },
  computed: {
    currentCategory() {
      return this.category.find((c) => c.id === this.selectedCategory);
    },
  },
  methods: {
    emitSelection() {
      this.$emit("category-selected", {
        index: this.index,
        id: this.selectedCategory,
      });
    },
  },
  components: {
    CategorySelect: () => import("./CategorySelect.vue"),
  },
};
</script>
