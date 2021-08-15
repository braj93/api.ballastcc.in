import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { CrmService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class UserCrmResolver implements Resolve<any> {
  constructor(
    private crmService: CrmService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.crmService.getList({
      user_id:route.params['id'],
      keyword: "",
      pagination: {
          offset: 0,
          limit: 10
        },
      sort_by:{
        column_name: '',
        order_by: '',
      },
      filter:""
    })
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
