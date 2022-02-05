import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MistLogoutComponent } from './mist-logout.component';

describe('MistLogoutComponent', () => {
  let component: MistLogoutComponent;
  let fixture: ComponentFixture<MistLogoutComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MistLogoutComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MistLogoutComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
