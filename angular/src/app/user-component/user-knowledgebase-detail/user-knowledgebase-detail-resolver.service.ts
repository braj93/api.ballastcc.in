import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminService, CrmService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class UserKnowledgebaseDetailResolver implements Resolve<any> {
  constructor(
    private crmService: CrmService,
    private adminService: AdminService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.adminService.getUserKnowledgebaseDetailById({knowledgebase_id :route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
