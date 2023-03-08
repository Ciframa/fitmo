<template>
  <div class="col-12-xs col-3-lg icons">
    <div class="icons__wrapper col-12-xs">
      <div
        class="icons__wrapper__item col-12-xs col-4-md col-12-lg"
        v-if="$route.name != 'vymena-a-vraceni-zbozi'"
      >
        <router-link to="vymena-a-vraceni-zbozi">
          <img src="../../public/assets/icons/changeandreturn.svg" alt="" />
          <span>Výměna a vrácení zboží</span>
        </router-link>
      </div>
      <div
        class="icons__wrapper__item col-12-xs col-4-md col-12-lg"
        v-if="$route.name != 'stav-objednavky'"
      >
        <router-link to="stav-objednavky">
          <img src="../../public/assets/icons/info.svg" alt="" />
          <span>Stav objednávky</span>
        </router-link>
      </div>
      <div
        class="icons__wrapper__item col-12-xs col-4-md col-12-lg"
        v-if="$route.name != 'doprava'"
      >
        <router-link to="doprava">
          <img src="../../public/assets/icons/logistics.svg" alt="" />
          <span>Doprava a platba</span>
        </router-link>
      </div>
      <div
        class="icons__wrapper__item col-12-xs col-4-md col-12-lg"
        v-if="$route.name != 'kontakt'"
      >
        <router-link to="kontakt">
          <img src="../../public/assets/icons/phone-bold.svg" alt="" />
          <span>Kontakty</span>
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>
export default {};
</script>

<style lang="scss">
.info .customMade__content {
  &__showroom {
    margin-bottom: 0;
  }
  .btn-gray {
    display: none;
  }
}
.customMade {
  background: $white;
  margin: auto;
  padding: 8rem 0;

  &__content {
    width: 90%;
    margin: auto;
    display: flex;
    flex-direction: column;

    & > a {
      margin: auto;
      font-size: 1.8rem;
      padding: 0.8rem 3rem;

      &:hover {
        background: $yellow;
        border-color: $yellow;
      }
    }

    article {
      margin: auto;
      text-align: center;

      width: 90%;
      margin-top: 2.6rem;
      max-width: 60rem;

      p {
        font-size: 1.5rem;
        line-height: 1.3em;
      }
      p:last-child {
        font-weight: 600;
        margin-top: 2rem;
        font-size: 1.6rem;
        color: $black-second;
      }
    }
    &__showroom {
      max-width: 1140px;
      display: flex;
      margin: 6rem auto 4rem auto;

      & > div {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin-bottom: 2rem;

        span {
          font-weight: 700;
          margin-top: 1.6rem;
          text-align: center;
          line-height: 1.6rem;
        }

        img {
          height: 19rem;
          margin: auto;
          width: 19rem;
          object-fit: cover;
          border-radius: 50%;
          &:last-child {
            display: none;
          }
        }
      }
    }
  }
}
@media screen and(min-width: $screen-md-min) {
  .customMade__content__showroom {
    & > div {
      position: relative;
      img {
        width: 14rem;
        height: 14rem;
        &:last-child {
          height: 4rem;
          width: 4rem;
          right: 0;
          top: 5rem;
          transform: translateX(50%);
          display: block;
          position: absolute;
        }
      }
    }
  }
}

@media screen and(min-width: $screen-lg-min) {
  .customMade__content {
    h2 {
      font-size: 3.96rem;
    }
    article {
      max-width: 90rem;
      p {
        font-size: 1.98rem;
        line-height: 2.3rem;
        &:last-child {
          font-size: 2.16rem;
        }
      }
    }
    &__showroom {
      margin-top: 11rem;
      & > div {
        img {
          width: 18rem;
          height: 18rem;

          &:last-child {
            height: 5rem;
            width: 5rem;
            top: 6.5rem;
          }
        }
      }
    }
  }
}
@media screen and(min-width: $screen-xl-min) {
  .customMade__content__showroom {
    & > div {
      img {
        width: 20rem;
        height: 20rem;

        &:last-child {
          height: 6rem;
          width: 6rem;
          top: 7rem;
        }
      }
    }
  }
}
</style>
