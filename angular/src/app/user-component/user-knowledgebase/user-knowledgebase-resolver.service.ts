import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminService, CrmService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class UserKnowledgebaseResolver implements Resolve<any> {
  constructor(
    private crmService: CrmService,
    private adminService: AdminService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.adminService.userKnowledgebaseList({
      // user_id:route.params['id'],
      keyword: "",
      pagination: {
          offset: 0,
          limit: 6
        },
      sort_by:{
        column_name: '',
        order_by: '',
      },
      filters: {
      category: ''
    }
    })
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
