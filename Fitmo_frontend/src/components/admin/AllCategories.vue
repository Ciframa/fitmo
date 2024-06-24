<template>
  <div class="row" style="flex-wrap: nowrap">
    <div class="col-12-xs">
      <input
        type="submit"
        v-on:click="addCategory()"
        value="Nový řádek"
        class="btn-yellow"
      />
      <input
        type="submit"
        v-on:click="updateCategories()"
        value="Upravit stav kategorií"
        class="btn-yellow"
      />
      <nested-draggable :categories="this.groupedCategories" />
    </div>
  </div>
</template>

<script>
import axios from "../../api";
import nestedDraggable from "./category/nested.vue";
export default {
  components: {
    nestedDraggable,
  },
  data() {
    return {
      groupedCategories: [],
      categories: [],
      categoryMaxId: 0,
    };
  },
  methods: {
    async getCategories() {
      try {
        const response = await axios.get("/api/categories");
        this.groupedCategories = this.groupByKeyReduce(response.data);
        this.categories = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    matchIdToParentRecursive(childrens, parentId = 0) {
      childrens.forEach((child, index) => {
        if (child.id_parent !== parentId) {
          child.id_parent = parentId;
        }

        if (child.children.length > 0) {
          this.matchIdToParentRecursive(child.children, child.id);
        }
      });
    },

    updateCategories() {
      this.groupedCategories.forEach((category) => {
        this.matchIdToParentRecursive(category.children, category.id);
      });
      const flattenedArray = this.flattenNestedArray(this.groupedCategories);
      console.log(flattenedArray);
      axios
        .post("/api/categories/update", [flattenedArray], {
          headers: {
            //  Authorization: this.tokenData.data.token,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.status == 200) {
            this.$snackbar.add({
              type: "success",
              text: "Jsi moc šikovný kluk 🎸!",
            });
          } else {
            this.$snackbar.add({
              type: "error",
              text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
            });
          }
        })
        .catch((error) => {
          this.$snackbar.add({
            type: "error",
            text: "Něco se 💩. Radši zavolej Márovi, než to celý rozbiješ.",
          });
        });
    },

    addCategory() {
      this.groupedCategories.unshift({
        id: this.categoryMaxId,
        name: "",
        id_parent: 0,
        path: "",
        index: 0,
        image: null,
        url_path: "",
        image_path: "",
        children: [],
      });
      this.categoryMaxId++;
    },

    flattenNestedArray(data) {
      const flattenedArray = [];

      function flatten(node, index) {
        flattenedArray.push({
          id: node.id,
          name: node.name,
          id_parent: node.id_parent,
          path: node.path,
          index: index,
          image: node.image,
          url_path: node.url_path,
          image_path: node.image_path,
        });

        if (node.children && node.children.length > 0) {
          for (let i = 0; i < node.children.length; i++) {
            flatten(node.children[i], i);
          }
        }
      }

      for (let i = 0; i < data.length; i++) {
        flatten(data[i], i);
      }

      return flattenedArray;
    },
    getCategoryMaxId() {
      axios
        .get("/api/category/maxId")
        .then((response) => {
          this.categoryMaxId = response.data;
          console.log(this.categoryMaxId);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    groupByKeyReduce(arr) {
      if (arr.length === 0) return;
      var map = {};
      var array = arr;
      for (var i = 0; i < array.length; i++) {
        var obj = array[i];
        obj.children = [];
        map[obj.id] = obj;
        var parent = obj.id_parent || "-";
        if (!map[parent]) {
          map[parent] = {
            children: [],
          };
        }
        map[parent].children.push(obj);
      }
      return map["-"].children;
    },
  },
  mounted() {
    this.getCategories();
    this.getCategoryMaxId();
  },
};
</script>
<style lang="scss" scoped>
ul {
  list-style-type: disc !important;
}
li {
  display: list-item !important;
  text-align: left !important;
  &::marker {
    unicode-bidi: isolate !important;
    font-variant-numeric: tabular-nums !important;
    text-transform: none !important;
    text-indent: 0px !important;
    text-align: start !important;
    text-align-last: start !important;
  }
}
</style>
