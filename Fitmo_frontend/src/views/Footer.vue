<template>
  <footer class="row">
    <div class="logo col-12-xs col-6-md">
      <img src="../../public/assets/logo.png" />
    </div>
    <div class="col-4-md col-2-lg col-12-xs">
      <h5>UŽITEČNÉ INFORMACE</h5>
      <ul>
        <li>
          <router-link to="/info/doprava">Doprava a platba</router-link>
        </li>
        <li>
          <router-link to="/info/kontakt">Kontakt</router-link>
        </li>
        <!--<li><a href="">O nás</a></li>-->
      </ul>
    </div>
    <div class="col-4-md col-2-lg col-12-xs">
      <h5>VŠE O NÁKUPU</h5>
      <ul>
        <li>
          <a
            href="../../assets/documents/Podmínky ochrany osobních údajů.pdf"
            download
          >
            <font-awesome-icon icon="fa-regular fa-file" />
            Zásady ochrany osobních údajů</a
          >
        </li>
        <li>
          <a href="../../assets/documents/Obchodní podmínky.pdf" download>
            <font-awesome-icon icon="fa-regular fa-file" /> Obchodní podmínky</a
          >
        </li>
        <li>
          <router-link to="/info/vymena-a-vraceni-zbozi"
            >Reklamace a vrácení zboží</router-link
          >
        </li>
      </ul>
    </div>
    <div class="col-4-md col-2-lg col-12-xs">
      <h5>KONTAKT</h5>
      <ul>
        <li>Telefon: +420 702 064 265</li>
        <li>Email: obchod@fitmo.cz</li>
        <li>IČ: 08679100</li>
      </ul>
    </div>
    <div class="col-6-md">
      <ul class="row">
        <li>
          <a href="https://www.instagram.com/fitmo.cz/">
            <img src="../../public/assets/icons/instagram.svg" alt="Login" />
          </a>
        </li>
        <li>
          <a href="https://www.facebook.com/fitmo.cz/">
            <img src="../../public/assets/icons/facebook.svg" alt="Login" />
          </a>
        </li>
      </ul>
    </div>
  </footer>
</template>

<script>
export default {
  name: "MainFooter",
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
footer {
  padding: 5rem 5%;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: space-between;
  background: $yellow;
  position: relative;
  font-size: 1.1rem;

  div:last-child {
    width: unset;
  }
  div:nth-child(4) {
    white-space: nowrap;
  }
  div {
    padding: 1rem 2rem;
  }

  img {
    height: unset;
    max-width: 17rem;
    margin-bottom: 2rem;
  }
  h5 {
    font-size: 1.4rem;
    font-weight: 500;
    white-space: nowrap;
  }

  ul li {
    line-height: 1.5rem;
    padding-top: 1rem;
    a {
      img {
        margin: 0 0.5rem;
        max-height: 4rem;
        width: 4rem;

        &:first-child {
          margin-left: 0;
        }
      }
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  footer {
    .logo {
      width: unset;
    }
  }
}

@media screen and (max-width: $screen-lg-min - 1px) {
  footer {
    text-align: center;

    & > * {
      margin: 2rem auto;
    }

    & > :nth-child(1) {
      order: 4;
      justify-content: flex-start;
    }

    & > :nth-child(5) {
      order: 5;

      .row {
        justify-content: flex-end;
      }
    }
  }
}
@media screen and (max-width: $screen-md-min - 1px) {
  footer {
    & > :nth-child(1) {
      order: 5;
      display: flex;
      justify-content: center;
    }

    & > :nth-child(2) {
      order: 1;
    }
    & > :nth-child(3) {
      order: 2;
    }
    & > :nth-child(4) {
      order: 3;
    }
    & > :nth-child(5) {
      order: 4;
    }
  }
}
</style>
