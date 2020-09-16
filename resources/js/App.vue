<template>
    <div class="weeklycard_container">
        <div v-for="(week, index) in weeks" :key="index">
          <router-link class="weeklycard" :to="{ name : 'week', params : { path: week.path }}">
            <div class="weeklytext">
              {{week.week}}曜日
            </div>
          </router-link>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
  name: 'App',
  components: { draggable },
  data() {
    return {
      weeks: [
        {path: 'mon', week: '月'},
        {path: 'tue', week: '火'},
        {path: 'wed', week: '水'},
        {path: 'thu', week: '木'},
        {path: 'fri', week: '金'},
        {path: 'sat', week: '土'},
        {path: 'sun', week: '日'}
      ],
    }
  },
}
</script>

<style lang="scss" scoped>
@import '../sass/variables';
.weeklycard_container{
  margin-top: 50px;
  display: flex;
  justify-content: space-around;
  flex-wrap:wrap;
  .weeklycard{
    margin-top: 10px;
    width: 180px;
    padding: 30px;
    display: block;
    text-decoration: none;
    text-align: center;
    background: $todo_card_bg;
    border: 1px solid  #707070;
    border-radius: 10px;
    // font-size: calc(2rem + ((1.5vw - 0.64rem) * 0.7143)); // 20px~24pxで可変
    font-size: 2.5rem;
    font-weight: bold;
    transition: .5s all;
    &:hover{
      background: $base_bg;
      box-shadow:0px 7px 10px 0px #707070;
      transform: translate(0, -5px);
      .weeklytext{
        color: $white;
      }
    }
    .weeklytext{
      color: $text_color;
      transition: .5s all;
    }
  }
}
</style>