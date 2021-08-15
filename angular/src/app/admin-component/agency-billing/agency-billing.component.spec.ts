import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AgencyBillingComponent } from './agency-billing.component';

describe('AgencyBillingComponent', () => {
  let component: AgencyBillingComponent;
  let fixture: ComponentFixture<AgencyBillingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AgencyBillingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AgencyBillingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
