<script>
import VueCharts from 'vue-chartjs'
import { Bar, Line,mixins} from 'vue-chartjs'
export default {
  extends: Bar,
   props: ['data', 'options'],
  mixins: [mixins.reactiveProp],
    data(){
       return {
           
       }
    },
    methods:{
    },
    mounted()
    {
      this.renderChart({
      labels: ['Sites', 'Agents', 'Farmers', 'Harvest', 'Transactions', 'Payments'],
            datasets: [
        {
          label: 'Our Harvest Report Summary',
          backgroundColor: '#0d6efd',
          data: this.data
        }
      ]
    }, {responsive: true, maintainAspectRatio: false})
    }
}
</script>