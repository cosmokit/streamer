import { sidebarItems, steps, progress, username } from "./mockData";

export const CyberpunkNeon = () => (
  <div className="w-full h-full flex" style={{ background: "#0a0014", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1" style={{ background: "#0d001a", borderRight: "1px solid #ff00ff33" }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{ color: "#ff00ff" }}>
        ⚡ STREAM<span style={{ color: "#00ffff" }}>PANEL</span>
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 rounded text-[8px]"
          style={{
            background: i === 0 ? "#ff00ff22" : "transparent",
            color: i === 0 ? "#ff00ff" : "#8866aa",
            borderLeft: i === 0 ? "2px solid #ff00ff" : "2px solid transparent",
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden">
      <div className="text-[9px] mb-1" style={{ color: "#00ffff88" }}>Привет, {username}</div>
      <div className="text-[13px] font-bold mb-3" style={{ color: "#ff00ff", textShadow: "0 0 10px #ff00ff66" }}>
        Мой прогресс
      </div>
      {/* Progress bar */}
      <div className="w-full h-2 rounded-full mb-4" style={{ background: "#1a0033" }}>
        <div
          className="h-full rounded-full"
          style={{
            width: `${progress}%`,
            background: "linear-gradient(90deg, #ff00ff, #00ffff)",
            boxShadow: "0 0 10px #ff00ff88",
          }}
        />
      </div>
      {/* Steps */}
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => (
          <div
            key={i}
            className="flex items-center gap-2 px-2 py-1.5 rounded text-[8px]"
            style={{
              background: step.done ? "#ff00ff11" : "#0d001a",
              border: `1px solid ${step.done ? "#ff00ff44" : "#ffffff08"}`,
              color: step.done ? "#ff00ff" : "#6644aa",
            }}
          >
            <span style={{ color: step.done ? "#00ffff" : "#333" }}>{step.done ? "✓" : "○"}</span>
            <span>{step.title}</span>
          </div>
        ))}
      </div>
    </div>
  </div>
);
