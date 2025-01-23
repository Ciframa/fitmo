module.exports = {
  css: {
    loaderOptions: {
      sass: {
        additionalData: `
          @import "bootstrap/dist/css/bootstrap.min.css";
          @import "@/scss/main.scss";              // Load custom styles after
        `,
        // @import "@/scss/main.scss";
      },
    },
  },
  chainWebpack: (config) => {
    config.plugin("html").tap((args) => {
      args[0].filename = "index.html";
      return args;
    });
  },
};
