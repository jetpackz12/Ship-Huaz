export function useFormatter() {
    const formatDate = (dateStr) => {
        const d = new Date(dateStr);
        return d.toLocaleDateString("en-PH", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    };

    const formatAmount = (amount) => {
        return new Intl.NumberFormat("en-PH", {
            style: "currency",
            currency: "PHP",
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatPhone = (phone) => {
        phone = phone.toString().trim();
        return phone.length === 10 ? `0${phone}` : phone;
    };

    return { formatDate, formatAmount, formatPhone };
}
