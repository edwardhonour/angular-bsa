//--
//-- The Data Service communicates with the database API function written in PHP asynchronisly. 
//-- The component or reslover calls a data service function and subscribes to it's results.  
//-- Control is passed back to the component because data retreival is not instant.  
//--
//-- For page navigation the route provider uses resolvers to coordinate the page load.
//-- For queries inside a component, the component subsribes to the results returned from 
//-- the data provider.
//--
//-- HttpClient is the method used.
//--
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  t: any;
  uid: any;
  url: any;
  un: any;
  role: any;
  production: any;

  constructor(private http: HttpClient) {

    //--
    //-- Tell the data service if we are getting data from MIST or a dev server.
    //--
    //-- Change this to Y before builds for MIST.
    //--
    
    this.production='N';

    if (this.production=='N') {
        this.url='https://edhonour.com/bsa/index.php';
        this.url='https://quadm.tech/bsa/index.php';
    } else {
        this.url='data/index.php';
    } 
        
  }

  getLocalStorage() {
    //--
    //-- Get the login variables before each API call.
    //--
    if (localStorage.getItem('uid')===null) {
      this.uid="";
    } else {
      this.uid=localStorage.getItem('uid')
    }

    if (localStorage.getItem('un')===null) {
      this.un="";
    } else {
      this.un=localStorage.getItem('un')
    }

    if (localStorage.getItem('role')===null) {
      this.role="";
    } else {
      this.role=localStorage.getItem('role')
    }
  }

  //--
  //-- Queries that return data
  //--
  getData(path: any, id: any, id2: any, id3: any) {
      this.getLocalStorage();
      const data = {
        "q" : path,         //-- Tells the API what function to call to return data.
        "id": id,           //-- Optional ID (1 of 3)
        "id2": id2,         //-- Optional ID (2 of 3)
        "id3": id3,         //-- Optional ID (3 of 3)
        "uid": this.uid     //-- The logged in USER_ID
      }
      this.t= this.http.post(this.url, data);
      return this.t;
  }

  //--
  //-- Posting data to the database.
  //-- formData contains the form that is sent to the API.
  //--
  postForm(formID: any, formData: any[]) {
    this.getLocalStorage();
    const data = {
      "q" : formID,
      "data": formData,
      "uid": this.uid
    }

  this.t= this.http.post(this.url, data);
  return this.t;

  }

  //-- Get the left vertical menu.
  //--
  getVerticalMenu() {
    this.getLocalStorage()
    const data = {
      "q" : "vertical-menu",
      "uid": this.uid,
      "role": this.role
    }

  if (this.production=='N') {
    this.t= this.http.post("https://edhonour.com/bsa/vertical.menu.php", data);
    this.t= this.http.post("https://quadm.tech/bsa/vertical.menu.php", data);
  } else {
    this.t= this.http.post("data/vertical.menu.php", data);
  }

  return this.t;

 }

  //-- Get info about the logged in user.
  //--
  //--
  getUser() {
    this.getLocalStorage()
    const data = {
      "q" : "vertical-menu",
      "uid": this.uid,
      "role": this.role
    }

    if (this.production=='N') {
      this.t= this.http.post("https://edhonour.com/bsa/get.user.php", data);
      this.t= this.http.post("https://quadm.tech/bsa/get.user.php", data);
    } else {
     this.t= this.http.post("data/get.user.php", data);
    }
    return this.t;

  }

  //-- Post a Document or Image upload.
  //--
  //--

  postUpload(filedata: any) {
    if (this.production=='N') {
      this.t=this.http.post('https://edhonour.com/bsa/upload.php',filedata);
    } else {
     this.t=this.http.post('data/upload.php',filedata);
    }     
    return this.t;
  }

}
