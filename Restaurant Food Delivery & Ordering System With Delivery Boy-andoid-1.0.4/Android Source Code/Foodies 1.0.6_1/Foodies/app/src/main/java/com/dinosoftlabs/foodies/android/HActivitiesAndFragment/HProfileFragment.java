package com.dinosoftlabs.foodies.android.HActivitiesAndFragment;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.RelativeLayout;

import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.ChangePasswordFragment;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.MainActivity;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RWeeklyEarningFragment;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderAccountInfoFragment;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderAppHelpFragment;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

/**
 * Created by Nabeel on 2/14/2018.
 */

public class HProfileFragment extends Fragment {
    RelativeLayout profile_div,log_out_div,weekly_earning_div,change_password_div,app_help_div;
    SharedPreferences profile_pref;
    public static boolean FLAG_ADMIN;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.hotel_profile_fragment, container, false);
        profile_pref = getContext().getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);

        FrameLayout frameLayout = v.findViewById(R.id.profile_main_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        init(v);

        return v;
    }

    public void init(View v){
        profile_div = v.findViewById(R.id.profile_div);
        log_out_div = v.findViewById(R.id.log_out_div);

        weekly_earning_div = v.findViewById(R.id.weekly_earning_div);

        change_password_div = v.findViewById(R.id.change_password_div);
        app_help_div = v.findViewById(R.id.app_help_div);


        log_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                logOutUser();

            }
        });

        profile_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                FLAG_ADMIN = true;
                Fragment restaurantMenuItemsFragment = new RiderAccountInfoFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();


            }
        });

        weekly_earning_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                FLAG_ADMIN = true;
                Fragment restaurantMenuItemsFragment = new RWeeklyEarningFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();

            }
        });


        app_help_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                FLAG_ADMIN = true;
                Fragment restaurantMenuItemsFragment = new RiderAppHelpFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"parent").commit();

            }
        });

        change_password_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                FLAG_ADMIN = true;
                Fragment restaurantMenuItemsFragment = new ChangePasswordFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.profile_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();

            }
        });


    }

    private void logOutUser(){
        SharedPreferences.Editor editor = profile_pref.edit();
        editor.putString(PreferenceClass.USER_TYPE,"");
        editor.putString(PreferenceClass.pre_email, "");
        editor.putString(PreferenceClass.pre_pass, "");
        editor.putString(PreferenceClass.pre_first, "");
        editor.putString(PreferenceClass.pre_last, "");
        editor.putString(PreferenceClass.pre_contact, "");
        editor.putString(PreferenceClass.pre_user_id, "");
        editor.putString(PreferenceClass.ADMIN_USER_ID,"");

        editor.putBoolean(PreferenceClass.IS_LOGIN, false);
        editor.commit();
        getActivity().startActivity(new Intent(getContext(), MainActivity.class));
        getActivity().finish();

    }

}
