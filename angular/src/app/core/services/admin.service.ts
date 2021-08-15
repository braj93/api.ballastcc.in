import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject, ReplaySubject } from 'rxjs';

import { ApiService } from './api.service';
import { JwtService } from './jwt.service';
import { User } from '../models';
import { map, distinctUntilChanged } from 'rxjs/operators';
import { Title, Meta } from '@angular/platform-browser';


@Injectable()
export class AdminService {
  constructor(
    private titleService: Title,
    private meta: Meta,
    private apiService: ApiService,
    private http: HttpClient,
    private jwtService: JwtService,
  ) { }

  // GET CONTENT LIST
  contentList(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/get_knowledgebase_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CONTENT LIST
  categoriesList(): Observable<User> {
    return this.apiService.get('/admin/knowledgebase/categories')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // CREATE KNOWLEDGEBASE
  createKnowledgebase(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/create_knowledgebase', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH KNOWLEDGEBASE DETAIL BY ID
  getKnowledgebaseDetailsById(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/get_knowledgebase_details_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE KNOWLEDGEBASE
  updateKnowledgebase(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/update_knowledgebase', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // DELETE KNOWLEDGEBASE
  deleteKnowledgebase(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/knowledgebase_delete', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET PLAN LIST
  plansList(): Observable<User> {
    return this.apiService.get('/admin/site_manage/plans')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET PLAN LIST
  getPlansByType(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_plans_by_type', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getMasterPlansByType(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_master_plans_by_type', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET USER KNOWLEDGEBASE LIST
  userKnowledgebaseList(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/user_knowledgebase_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH  USER KNOWLEDGEBASE DETAIL BY ID
  getUserKnowledgebaseDetailById(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/user_knowledgebase_detail', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET USER KNOWLEDGEBASE RELATED STUFF LIST
  userKnowledgebaseRelatedStuffList(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/user_knowledgebase_related_stuff_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // CHANGE STATUS IN KNOWLEDGEBASE
  changeKnowledgebaseStatus(input: any): Observable<User> {
    return this.apiService.post('/admin/knowledgebase/change_status', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CATEGORIES LIST
  getCategoryList(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_category_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }


  // CREATE CATEGORY
  createCategory(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/create_category', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }


  // ADD BROADCAST
  addBroadcast(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/send_message', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // EDIT BROADCAST
  updateBroadcast(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/update_broadcast', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // BROADCAST LIST
  getBroadcastList(input: any): Observable<User> {
    return this.apiService.post('/admin/Broadcast/list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH  BROADCAST DETAILS
  getBroadcastDetail(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/get_broadcast_detail', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getCategoryDetailById(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_category_details_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE CATEGORY
  updateCategory(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/update_category', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // SEND RESET PASSWORD LINK
  resetPasswordLink(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/reset_password_link', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET PLANS
  getPlans(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_plans', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // BROADCAST USER LIST
  getBroadcastListByUser(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/broadcast_list_by_user', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH  BROADCAST DETAILS  BY ID
  getBroadcastDetailById(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/get_broadcast_detail_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH  BROADCAST DETAILS  BY ID
  updateBroadcastStatus(input: any): Observable<User> {
    return this.apiService.post('/admin/broadcast/update_broadcast_seen_status', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET PRICING PLANS LIST
  getPricingPlansList(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_pricing_plans', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // CREATE PRICING PLAN
  createPricingPlan(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/create_pricing_plan', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // FETCH  PRICING PLAN DETAILS
  getPricingPlanDetails(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_pricing_plan_details', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE PRICING PLAN
  updatePricingPlan(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/update_pricing_plan', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET PRICING PLANS BY TYPE
  getPricingPlansByType(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/get_pricing_plans_by_type', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE PRICING PLAN
  deletePricingPlan(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/delete_pricing_plan', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // CHANGE USER STATUS
  changePricingPlanStatus(input: any): Observable<User> {
    return this.apiService.post('/admin/site_manage/change_pricing_plan_status', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

}
