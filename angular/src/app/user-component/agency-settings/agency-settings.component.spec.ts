import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AgencySettingsComponent } from './agency-settings.component';

describe('AgencySettingsComponent', () => {
  let component: AgencySettingsComponent;
  let fixture: ComponentFixture<AgencySettingsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AgencySettingsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AgencySettingsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
