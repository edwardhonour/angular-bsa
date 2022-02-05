//--
//-- This auth guard is used by the route provider to ensure users are logged into MIST
//-- and not trying to access the application without logging in.
//--
//-- This code is redundant because the PULSE device will force a logout if the user attempts 
//-- to access the server.
//--
import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, Router } from '@angular/router';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthGuardOriginal implements CanActivate {

  isConnected: any;
  isLoggedIn: any;

  constructor(
    public router: Router
  ){ }

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
      if (localStorage.getItem('uid')===null) {
          this.router.navigate(['/mist-logout'])  
      } else {
        if (localStorage.getItem('uid')==""||localStorage.getItem('uid')=="0") {
          this.router.navigate(['/mist-logout'])
        }
      }
    return true;
  }
}