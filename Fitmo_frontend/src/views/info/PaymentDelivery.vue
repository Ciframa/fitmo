<template>
  <div class="payment row">
    <div class="col-12-xs col-9-lg info__header__main">
      <h1>Doprava a platba</h1>
      <article>
        <p>
          V případě objednávek s celkovou hmotností do 5 kg doporučujeme zvolit
          Zásilkovnu, s hmotností do 10 kg Nejvýhodnější doprava na adresu, do
          15 kg Balíkovnu, které nabízí rychlé a kvalitní služby. Jakmile je
          celková hmotnost objednávky nad 10 či 20 kg, ve většině případů Vaši
          objednávku doručí 123kurýr nebo Česká pošta.
        </p>
        <div class="row">
          <img src="../../../public/assets/icons/logistics.svg" alt="" />
          <span>Doprava zdarma <br />při nákupu nad 1999 Kč</span>
        </div>
      </article>
      <div class="payment__info_wrapper">
        <h2>Možnosti dopravy</h2>
        <section>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img
                src="../../../public/assets/carriers/zasilkovna.jpg"
                alt=""
              />
              <span>Zásilkovna: balík do zásilkovny</span>
            </div>
            <span class="payment__info_wrapper__item__price">60 Kč</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img src="../../../public/assets/carriers/ppl.jpg" alt="" />
              <span>PPL: balík na adresu</span>
            </div>
            <span class="payment__info_wrapper__item__price">75 Kč</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img src="../../../public/assets/carriers/cp.jpg" alt="" />
              <span>Česká pošta</span>
            </div>
            <span class="payment__info_wrapper__item__price">od 80 Kč</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img src="../../../public/assets/carriers/dhl.jpg" alt="" />
              <span>DHL: balík do zahraničí</span>
            </div>
            <span class="payment__info_wrapper__item__price">250 Kč</span>
          </div>
        </section>
      </div>
      <div class="payment__info_wrapper">
        <h2>Způsoby platby</h2>
        <section>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img src="../../../public/assets/icons/paying/card.svg" alt="" />
              <span>Kartou on-line</span>
            </div>
            <span class="payment__info_wrapper__item__price">ZDARMA</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img
                src="../../../public/assets/icons/paying/transferToAcc.svg"
                alt=""
              />
              <span>Převodem na účet</span>
            </div>
            <span class="payment__info_wrapper__item__price">ZDARMA</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img
                src="../../../public/assets/icons/paying/cashOnDelivery.svg"
                alt=""
              />
              <span>Dobírkou</span>
            </div>
            <span class="payment__info_wrapper__item__price">60 Kč</span>
          </div>
          <div class="payment__info_wrapper__item">
            <font-awesome-icon :icon="['fa', 'angle-down']" size="2x" />
            <div class="payment__info_wrapper__item__textImg">
              <img
                src="../../../public/assets/icons/paying/twisto.svg"
                alt=""
              />
              <span>Platbou přes Twisto: objednej zaplať do 14 dnů</span>
            </div>
            <span class="payment__info_wrapper__item__price">ZDARMA</span>
          </div>
        </section>
      </div>
    </div>
    <side-bar-links></side-bar-links>
  </div>
</template>

<script>
import SideBarLinks from "../../components/SideBarInfoLinks.vue";

export default {
  components: { SideBarLinks },
};
</script>
<style lang="scss">
.payment {
  padding: 5rem 10% 4rem 10%;
  background: $gray-third;
  article {
    margin-top: 2.2rem;
    color: $black-second;

    p {
      text-align: justify;
      font-size: 1.5rem;
    }
    .row {
      align-items: center;
      margin: 5rem 0;
      img {
        height: 4rem;
      }
      span {
        margin-left: 2rem;
        font-size: 1.6rem;
        line-height: 2rem;
        font-weight: 1000;
      }
    }
  }
  &__info_wrapper {
    width: 100%;

    &:nth-child(2) {
      margin-bottom: 3rem;
    }
    h2 {
      text-align: left;
      font-size: 1.5rem;
      margin-bottom: 0.6rem;
    }
    section {
      background: $white;
      border-radius: 2rem;
      padding: 2rem 4rem 2rem 3rem;
    }

    &__item {
      display: flex;
      padding: 1rem 0;

      > svg {
        color: $gray-second;
      }
      &__textImg {
        margin-left: 2.5rem;
        align-items: center;
        span {
          font-size: 1.2rem;
          display: block;
          line-height: 1.5rem;
          margin-top: 0.7rem;
          font-weight: 400;
        }
        img {
          height: 2.5rem;
          max-width: 15rem;
          max-height: 100%;
          border-radius: 0.5rem;
        }
      }
      &__price {
        margin-left: auto;
        font-weight: 500;
        font-size: 1.4rem;
        white-space: nowrap;
      }
    }
    &:last-child {
      section {
        .payment__info_wrapper__item__textImg {
          span {
            padding-right: 2.5rem;
          }
          img {
            width: 3.5rem;
          }
        }
      }
    }
  }
}
@media screen and (max-width: $screen-sm-min -1px) {
  .payment__info_wrapper {
    &__item__textImg span {
      line-height: 1.4rem;
    }
  }
}
@media screen and (min-width: $screen-sm-min) {
  .payment__info_wrapper__item__textImg {
    img {
      height: 3.4rem;
    }
  }
}

@media screen and (min-width: $screen-md-min) {
  .payment__info_wrapper__item {
    align-items: center;

    &__textImg {
      display: flex;

      span {
        margin: 0 1.5rem;
      }
    }
  }
}
@media screen and (min-width: $screen-lg-min) {
  .payment__info_wrapper__item__textImg span {
    font-size: 1.4rem;
  }
}
</style>
