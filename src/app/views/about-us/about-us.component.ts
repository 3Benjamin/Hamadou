import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-about-us',
  templateUrl: './about-us.component.html',
  styleUrls: ['./about-us.component.scss']
})
export class AboutUsComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }
    data = {
      title:`Team WADABA team is a group of young aspiring African scholars from different nationalities. They are all experts
      in the field of Water who came together under the Pan African University Institute of Water and Energy Sciencesincluding climate change (PAUWES)-Algeria and developed an innovative idea-Water Data Bank, geared towards the
      provision of solutions related to water pollution in the African continent and Likasi in the Democratic Republic of
      Congo in particular.`,
      teams:[
        {
          title:'Ewube',
          description:`Ewube Kelly Laure Egbe is an AU Scholar studying Water Policy at the Pan African University 
          Institute of Water and Energy Sciences including climate change PAUWES Algeria and currently 
          researching on Climate Change and WASH within rural communities. She obtained a BSc in Geography 
          from the University of Buea, Buea-Cameroon.  She is a robust and dynamic young fellow of Geography 
          and Policy Issues interested in social and environmental problems such as plastic wastes solutions.`,
          img:'assets/img/team/Ewube.png'
        },
        {
          title:'Christian',
          description:`Christian MURHULA SHABURWA is an African Union Scholar from DRCongo and currently pursuing a 
          Master of Science in Water Policy at Pan African University Institute of Water and Energy Sciences, 
          Algeria. Christian studied Process Engineering during his undergraduate at Djilali Liabes University,
           Algeria.  Interested in entrepreneurship, Christian was the third winner of the HultPrize. 
           competition at his university in 2019.`,
           img:'assets/img/team/Christian.png'
        },
        {
          title:'Brendaline',
          description:`NKENEN Brendaline Shieke is a PAUWES student in the water engineering track with an Agricultural 
          and environmental engineering Bsc background. She has experience with NGOs as a volunteer focused 
          on fighting against food insecurity which comes from food shortages and low-quality produce. 
          she was a member of the Next Water Generation Action. She is currently researching on the risks 
          and opportunities in rice production in Cameroon through a water productivity. approach using 
          remote sensing data.`,
          img:'assets/img/team/Brenda.png'
        },
        {
          title:'Cresus',
          description:`Crésus Hammer KODONGO NDROU is a multi-talented engineer of Central African origin. 
          He is currently training at Pan African University Institute of Water and Energy Sciences, 
          Algeria Pauwes where he does water engineering. Always in search of knowledge, he has proven himself 
          in the field of rural hydraulics, before working as a Wash Facilitator and as Responsible of 
          the sustainable project at the National Agency of Water and Sanitation in Central Africa.`,
          img:'assets/img/team/Cresus.png'
        },
        {
          title:'Benjamin',
          description:`Benjamin Bonkoungou is currently pursuing his Master in Water Engineering at the 
          Pan African University Institute of Water and Energy Sciences including Climate Change (PAUWES), 
          Algeria. Prior to joining PAUWES, he majored in Environmental Engineering from Erciyes University, 
          Turkey; and has worked on a couple of development projects. Benjamin is from Burkina Faso..`,
          img:'assets/img/team/Benjo.png'
        },
      ]
    }

}
