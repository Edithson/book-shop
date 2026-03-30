<!-- Stats grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <template x-for="stat in stats" :key="stat.label">
            <div class="stat-card p-5">
              <div class="flex items-start justify-between mb-3">
                <div class="text-2xl" x-text="stat.icon"></div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                  :class="stat.trend > 0 ? 'bg-sage/10 text-sage' : 'bg-rust/10 text-rust'"
                  x-text="(stat.trend > 0 ? '+' : '') + stat.trend + '%'"></span>
              </div>
              <div class="font-serif font-bold text-2xl text-ink" x-text="stat.value"></div>
              <div class="text-xs text-ink/40 mt-0.5" x-text="stat.label"></div>
            </div>
          </template>
        </div>
