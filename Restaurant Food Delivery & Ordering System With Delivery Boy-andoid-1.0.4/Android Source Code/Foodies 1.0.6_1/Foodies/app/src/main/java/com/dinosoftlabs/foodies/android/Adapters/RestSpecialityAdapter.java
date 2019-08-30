package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Models.SpecialityModel;
import com.dinosoftlabs.foodies.android.R;


import java.util.ArrayList;

/**
 * Created by Nabeel on 1/26/2018.
 */

public class RestSpecialityAdapter extends RecyclerView.Adapter<RestSpecialityAdapter.ViewHolder> implements Filterable {

    ArrayList<SpecialityModel> getDataAdapter;
    private ArrayList<SpecialityModel> mFilteredList;
    Context context;

    CountryListAdapter.OnItemClickListner onItemClickListner;

    public RestSpecialityAdapter(ArrayList<SpecialityModel> getDataAdapter, Context context){
        super();
        this.getDataAdapter = getDataAdapter;
        this.mFilteredList = getDataAdapter;
        this.context = context;

    }

    @Override
    public RestSpecialityAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_item_rest_speciality, parent, false);

        RestSpecialityAdapter.ViewHolder viewHolder = new RestSpecialityAdapter.ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(RestSpecialityAdapter.ViewHolder holder, final int position) {


        holder.name_tv.setText(mFilteredList.get(position).getName());

        holder.main_speciality.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                onItemClickListner.OnItemClicked(v, position);
            }
        });


    }


    @Override
    public int getItemCount() {
        return mFilteredList.size() ;
    }
    @SuppressWarnings("unchecked")
    @Override
    public Filter getFilter() {

        return new Filter() {
            @Override
            protected FilterResults performFiltering(CharSequence charSequence) {
                String charString = charSequence.toString();
                if (charString.isEmpty()) {
                    mFilteredList = getDataAdapter;
                } else {
                    ArrayList<SpecialityModel> filteredList = new ArrayList<>();
                    for (SpecialityModel row : getDataAdapter) {

                        // name match condition. this might differ depending on your requirement
                        // here we are looking for name or phone number match
                        if (row.getName().toLowerCase().contains(charString.toLowerCase()) || row.getName().contains(charSequence)) {
                            filteredList.add(row);
                        }
                    }

                    mFilteredList = filteredList;
                }

                FilterResults filterResults = new FilterResults();
                filterResults.values = mFilteredList;
                return filterResults;
            }

            @Override
            protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
                mFilteredList = (ArrayList<SpecialityModel>) filterResults.values;
                notifyDataSetChanged();
            }
        };

    }


    class ViewHolder extends RecyclerView.ViewHolder{

     TextView name_tv;
     RelativeLayout main_speciality;

        public ViewHolder(View itemView) {

            super(itemView);

           name_tv = itemView.findViewById(R.id.name_tv);
            main_speciality = itemView.findViewById(R.id.main_speciality);


        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(CountryListAdapter.OnItemClickListner onCardClickListner) {
        this.onItemClickListner = onCardClickListner;
    }
}