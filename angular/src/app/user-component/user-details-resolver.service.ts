import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { UserService } from '../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class UserDetailsResolver implements Resolve<any> {
  constructor(
    private userService: UserService,
    private router: Router
  ) { }
  resolve(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<any> {
    return this.userService.getDetailByUserId({ user_id: route.params['id'] })
      .pipe(catchError((err) => this.router.navigateByUrl('/')));
  }
}
