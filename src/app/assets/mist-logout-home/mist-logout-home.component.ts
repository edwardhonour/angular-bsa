import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-mist-logout-home',
  templateUrl: './mist-logout-home.component.html',
  styleUrls: ['./mist-logout-home.component.scss']
})
export class MistLogoutHomeComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
    let hostname=localStorage.getItem('hostname')
    let url='https://'+hostname+'/ctoday.asp'
    location.href=url;
  }                                                                   8
}
