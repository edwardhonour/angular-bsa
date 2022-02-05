import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MistLogoutHomeComponent } from './mist-logout-home.component';

describe('MistLogoutHomeComponent', () => {
  let component: MistLogoutHomeComponent;
  let fixture: ComponentFixture<MistLogoutHomeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MistLogoutHomeComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MistLogoutHomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
